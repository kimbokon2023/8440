
import React, { useState, useEffect } from 'react';
import { AppState, MaterialSlot, FloorMode, FloorPreset, MaterialMode, PresetType, PresetColor } from './types';
import { generateElevatorRender } from './services/geminiService';
import FileUpload from './components/FileUpload';
import MaterialGrid from './components/MaterialGrid';

const INITIAL_PANELS: MaterialSlot[] = Array.from({ length: 11 }, (_, i) => ({
  id: (i + 1).toString(),
  label: `Panel ${i + 1}`,
  mode: 'upload',
  presetType: 'hairline',
  presetColor: 'silver',
  file: null,
  previewUrl: null
}));

// Helper to determine closest supported Gemini aspect ratio
const getClosestAspectRatio = (width: number, height: number): string => {
  const ratio = width / height;
  const supportedRatios = [
    { id: "1:1", value: 1.0 },
    { id: "3:4", value: 0.75 },
    { id: "4:3", value: 1.333 },
    { id: "9:16", value: 0.5625 },
    { id: "16:9", value: 1.777 },
  ];
  
  // Find the one with minimum difference
  const closest = supportedRatios.reduce((prev, curr) => {
    return (Math.abs(curr.value - ratio) < Math.abs(prev.value - ratio) ? curr : prev);
  });
  
  return closest.id;
};

const App: React.FC = () => {
  // API Key State
  const [hasApiKey, setHasApiKey] = useState(false);
  const [checkingKey, setCheckingKey] = useState(true);

  // Application State
  const [appState, setAppState] = useState<AppState>(AppState.IDLE);
  const [layoutImage, setLayoutImage] = useState<{ file: File; preview: string } | null>(null);
  const [layoutAspectRatio, setLayoutAspectRatio] = useState<string>("1:1");
  
  const [doorImage, setDoorImage] = useState<{ file: File; preview: string } | null>(null);
  
  // Floor State
  const [floorMode, setFloorMode] = useState<FloorMode>('upload');
  const [floorImage, setFloorImage] = useState<{ file: File; preview: string } | null>(null);
  const [floorPreset, setFloorPreset] = useState<FloorPreset>('deco-tile');

  // Lighting & Reflection State
  const [lightingTemp, setLightingTemp] = useState<number>(2000);
  const [reflectionIntensity, setReflectionIntensity] = useState<number>(50);

  const [panels, setPanels] = useState<MaterialSlot[]>(INITIAL_PANELS);
  const [resultImage, setResultImage] = useState<string | null>(null);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);

  // Timer State
  const [elapsedTime, setElapsedTime] = useState<number>(0);

  // Button Feedback State
  const [isApplied, setIsApplied] = useState(false);

  // Check for API Key on Mount
  useEffect(() => {
    const checkKey = async () => {
      try {
        // @ts-ignore - aistudio is injected
        if (window.aistudio && await window.aistudio.hasSelectedApiKey()) {
          setHasApiKey(true);
        }
      } catch (e) {
        console.error("Error checking API key:", e);
      } finally {
        setCheckingKey(false);
      }
    };
    checkKey();
  }, []);

  // Timer Effect
  useEffect(() => {
    let interval: any;
    
    if (appState === AppState.GENERATING) {
      interval = setInterval(() => {
        setElapsedTime(prev => prev + 1);
      }, 1000);
    } else {
      // Reset timer when not generating (or keep it if you want to show final time)
      if (appState === AppState.IDLE) {
        setElapsedTime(0);
      }
    }

    return () => clearInterval(interval);
  }, [appState]);

  const formatTime = (seconds: number) => {
    const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
    const secs = (seconds % 60).toString().padStart(2, '0');
    return `${mins}분 ${secs}초`;
  };

  const handleConnectKey = async () => {
    // @ts-ignore - aistudio is injected
    if (window.aistudio) {
      try {
        // @ts-ignore
        await window.aistudio.openSelectKey();
        // Assuming success after dialog closes or promise resolves
        setHasApiKey(true);
      } catch (error) {
        console.error("API Key selection failed:", error);
        // Reset state to force retry if needed, though usually we assume success
        setErrorMessage("Key selection was cancelled or failed. Please try again.");
      }
    }
  };

  // Handlers
  const handleLayoutSelect = (file: File) => {
    const url = URL.createObjectURL(file);
    
    // Calculate aspect ratio
    const img = new Image();
    img.onload = () => {
      const ratioId = getClosestAspectRatio(img.width, img.height);
      console.log(`Detected layout dimensions: ${img.width}x${img.height}. Closest ratio: ${ratioId}`);
      setLayoutAspectRatio(ratioId);
    };
    img.src = url;

    setLayoutImage({ file, preview: url });
  };

  const handleDoorSelect = (file: File) => {
    const url = URL.createObjectURL(file);
    setDoorImage({ file, preview: url });
  };

  const handleFloorSelect = (file: File) => {
    const url = URL.createObjectURL(file);
    setFloorImage({ file, preview: url });
  };

  const handlePanelUpdateFile = (id: string, file: File) => {
    const url = URL.createObjectURL(file);
    setPanels(prev => prev.map(p => 
      p.id === id ? { ...p, file: file, previewUrl: url } : p
    ));
  };

  const handlePanelRemoveFile = (id: string) => {
    setPanels(prev => prev.map(p => 
      p.id === id ? { ...p, file: null, previewUrl: null } : p
    ));
  };

  const handlePanelUpdateMode = (id: string, mode: MaterialMode) => {
    setPanels(prev => prev.map(p => 
      p.id === id ? { ...p, mode: mode } : p
    ));
  };

  const handlePanelUpdatePreset = (id: string, type: PresetType, color: PresetColor) => {
    setPanels(prev => prev.map(p => 
      p.id === id ? { ...p, presetType: type, presetColor: color } : p
    ));
  };

  // Updated logic: Applies Panel 1 Texture (or Preset) to all panels (1-11)
  const handleApplyPanel1ToAll = () => {
    // Explicitly finding Panel 1 (Index 0)
    const panel1 = panels[0];
    
    // Check if Panel 1 is ready (either has file or is in preset mode)
    if (panel1.mode === 'upload' && !panel1.file) {
      alert("Please upload a texture for Panel 1 first.\n먼저 패널 1의 재질을 업로드해주세요.");
      return;
    }

    // Capture source properties
    const sourceMode = panel1.mode;
    const sourceFile = panel1.file;
    const sourcePreview = panel1.previewUrl;
    const sourcePresetType = panel1.presetType;
    const sourcePresetColor = panel1.presetColor;

    setPanels(prev => prev.map(p => {
      return {
        ...p,
        mode: sourceMode,
        file: sourceFile,
        previewUrl: sourcePreview,
        presetType: sourcePresetType,
        presetColor: sourcePresetColor
      };
    }));

    // Show feedback
    setIsApplied(true);
    setTimeout(() => setIsApplied(false), 2000);
  };

  const handleGenerate = async () => {
    if (!layoutImage) {
      setErrorMessage("Please upload a Layout Plan image first.");
      return;
    }
    
    setElapsedTime(0);
    setAppState(AppState.GENERATING);
    setErrorMessage(null);

    const floorConfig = {
      mode: floorMode,
      file: floorImage?.file || null,
      preset: floorPreset
    };

    try {
      const result = await generateElevatorRender(
        layoutImage.file,
        doorImage?.file || null,
        floorConfig,
        panels,
        layoutAspectRatio,
        lightingTemp,
        reflectionIntensity
      );
      setResultImage(result);
      setAppState(AppState.SUCCESS);
    } catch (error: any) {
      console.error(error);
      if (error.message && error.message.includes("Requested entity was not found")) {
         setErrorMessage("The selected API Key project may not have access or is invalid. Please select a valid paid project key.");
         setHasApiKey(false); // Force re-selection
      } else {
         setErrorMessage("Failed to generate rendering. Please check your inputs.");
      }
      setAppState(AppState.ERROR);
    }
  };

  const handleReset = () => {
    setAppState(AppState.IDLE);
    setResultImage(null);
    setElapsedTime(0);
  };

  // Check if Panel 1 has valid config
  const isPanel1Ready = panels[0].mode === 'preset' || (panels[0].mode === 'upload' && panels[0].file !== null);

  // Render Landing Page if no API key
  if (!checkingKey && !hasApiKey) {
    return (
      <div className="min-h-screen bg-slate-950 flex flex-col items-center justify-center p-4 text-center">
        <div className="w-20 h-20 bg-gradient-to-tr from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-2xl shadow-purple-500/30 mb-8">
           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="w-10 h-10 text-white"><path d="m2 22 1-1h3l9-9"/><path d="M13 6.17V11h4.83"/><path d="M15.19 15.19 18 18"/></svg>
        </div>
        <h1 className="text-4xl font-bold text-white mb-4">ElevatorViz <span className="text-purple-400">Pro</span></h1>
        <p className="text-slate-400 max-w-md mb-8 text-lg">
          To use the high-quality <b>Gemini 3.0 Pro Image</b> model (Nano Banana Pro), please connect a Google Cloud Project with billing enabled.
        </p>
        <button 
          onClick={handleConnectKey}
          className="bg-white text-slate-900 px-8 py-4 rounded-xl font-bold text-lg hover:bg-slate-200 transition-colors shadow-lg flex items-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
          Connect API Key
        </button>
        <p className="mt-6 text-xs text-slate-600">
          Make sure your project has the Gemini API enabled and billing set up.<br/>
          <a href="https://ai.google.dev/gemini-api/docs/billing" target="_blank" rel="noopener noreferrer" className="underline hover:text-slate-400">View Billing Documentation</a>
        </p>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-slate-950 text-slate-200 selection:bg-purple-500/30">
      {/* Header */}
      <header className="sticky top-0 z-50 bg-slate-950/80 backdrop-blur-md border-b border-slate-800">
        <div className="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="w-8 h-8 bg-gradient-to-tr from-purple-500 to-indigo-500 rounded-lg flex items-center justify-center shadow-lg shadow-purple-500/20">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" className="w-5 h-5 text-white"><path d="m2 22 1-1h3l9-9"/><path d="M13 6.17V11h4.83"/><path d="M15.19 15.19 18 18"/></svg>
            </div>
            <h1 className="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-indigo-400">
              ElevatorViz <span className="text-slate-500 font-normal text-sm ml-1">Pro</span>
            </h1>
          </div>
          <div className="flex items-center gap-2">
            <button 
               onClick={handleConnectKey}
               className="text-xs font-mono text-purple-300 bg-purple-900/30 px-3 py-1.5 rounded border border-purple-500/30 hover:bg-purple-900/50 transition-colors flex items-center gap-2"
               title="Change API Key"
            >
              <span className="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
              gemini-3-pro-image-preview
            </button>
          </div>
        </div>
      </header>

      <main className="max-w-7xl mx-auto px-4 py-8">
        <div className="flex flex-col lg:flex-row gap-8">
          
          {/* LEFT COLUMN: Controls */}
          <div className="w-full lg:w-5/12 space-y-8">
            
            {/* 1. Layout Section */}
            <section className="bg-slate-900 rounded-2xl p-6 border border-slate-800 shadow-xl">
              <div className="flex items-center justify-between mb-4">
                <h2 className="text-lg font-semibold text-white flex items-center gap-2">
                  <span className="flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-xs font-bold">1</span>
                  Structural Layout
                </h2>
                <div className="flex items-center gap-2">
                  {layoutImage && (
                    <span className="text-xs text-indigo-400 font-mono bg-indigo-900/30 px-2 py-0.5 rounded border border-indigo-500/30">
                      Ratio: {layoutAspectRatio}
                    </span>
                  )}
                  <span className="text-xs text-slate-500 uppercase tracking-wider">Required</span>
                </div>
              </div>
              <p className="text-sm text-slate-400 mb-4">Upload the wireframe or line drawing of the elevator case. <br/><span className="text-xs text-slate-500">Supported ratios will be matched automatically (1:1, 4:3, 16:9, etc).</span></p>
              <FileUpload
                label=""
                subLabel="Drop Layout Plan Here"
                previewUrl={layoutImage?.preview || null}
                onFileSelect={handleLayoutSelect}
                onClear={() => {
                  setLayoutImage(null);
                  setLayoutAspectRatio("1:1");
                }}
                className="h-48"
              />
            </section>

            {/* 2. Materials & Settings Section */}
            <section className="bg-slate-900 rounded-2xl p-6 border border-slate-800 shadow-xl">
              <div className="flex items-center justify-between mb-4">
                <h2 className="text-lg font-semibold text-white flex items-center gap-2">
                  <span className="flex items-center justify-center w-6 h-6 rounded-full bg-purple-600 text-xs font-bold">2</span>
                  Materials & Lighting
                </h2>
                <span className="text-xs text-slate-500 uppercase tracking-wider">Optional</span>
              </div>
              
              <div className="space-y-6">
                
                {/* Lighting & Reflection Control */}
                <div className="bg-slate-950/50 p-4 rounded-xl border border-slate-800 space-y-4">
                  {/* Color Temp */}
                  <div>
                    <div className="flex items-center justify-between mb-2">
                      <label className="text-sm font-medium text-slate-300">Lighting Color (Kelvin)</label>
                      <span className="text-xs font-mono text-amber-300 bg-amber-900/30 px-2 py-0.5 rounded border border-amber-500/30">
                        {lightingTemp}K
                      </span>
                    </div>
                    <div className="flex items-center gap-4">
                      <div className="flex-1 relative">
                        <input 
                          type="range" 
                          min="2000" 
                          max="6500" 
                          step="100" 
                          value={lightingTemp} 
                          onChange={(e) => setLightingTemp(parseInt(e.target.value))}
                          className="w-full h-2 bg-gradient-to-r from-orange-500 via-yellow-100 to-blue-300 rounded-lg appearance-none cursor-pointer"
                        />
                      </div>
                      <div className="w-20">
                         <input 
                           type="number" 
                           value={lightingTemp}
                           onChange={(e) => setLightingTemp(parseInt(e.target.value))}
                           className="w-full bg-slate-800 border border-slate-700 rounded px-2 py-1 text-sm text-center text-white focus:outline-none focus:border-purple-500"
                         />
                      </div>
                    </div>
                  </div>

                  {/* Reflection Intensity */}
                  <div className="pt-2 border-t border-slate-800">
                    <div className="flex items-center justify-between mb-2">
                      <label className="text-sm font-medium text-slate-300">Reflection Intensity (반사도)</label>
                      <span className="text-xs font-mono text-cyan-300 bg-cyan-900/30 px-2 py-0.5 rounded border border-cyan-500/30">
                        {reflectionIntensity}%
                      </span>
                    </div>
                    <div className="flex items-center gap-4">
                      <div className="flex-1 relative">
                         <input 
                           type="range" 
                           min="0" 
                           max="100" 
                           step="10" 
                           value={reflectionIntensity} 
                           onChange={(e) => setReflectionIntensity(parseInt(e.target.value))}
                           className="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer"
                         />
                      </div>
                      <span className="text-xs text-slate-400 w-20 text-center">
                         {reflectionIntensity < 30 ? 'Matte' : reflectionIntensity < 70 ? 'Standard' : 'High Gloss'}
                      </span>
                    </div>
                  </div>
                </div>

                {/* Door */}
                <div className="bg-slate-950/50 p-4 rounded-xl border border-slate-800">
                  <FileUpload
                    label="Entrance Door Material (출입구 도어)"
                    subLabel="Upload metal, glass, etc."
                    previewUrl={doorImage?.preview || null}
                    onFileSelect={handleDoorSelect}
                    onClear={() => setDoorImage(null)}
                  />
                </div>

                {/* Floor Material */}
                <div className="bg-slate-950/50 p-4 rounded-xl border border-slate-800">
                  <div className="flex items-center justify-between mb-3">
                    <label className="text-sm font-medium text-slate-300">Floor Material (바닥 재질)</label>
                    <div className="flex bg-slate-800 rounded-lg p-1 border border-slate-700">
                      <button
                        onClick={() => setFloorMode('upload')}
                        className={`text-xs px-3 py-1 rounded-md transition-all ${floorMode === 'upload' ? 'bg-slate-600 text-white shadow' : 'text-slate-400 hover:text-white'}`}
                      >
                        Image
                      </button>
                      <button
                        onClick={() => setFloorMode('preset')}
                        className={`text-xs px-3 py-1 rounded-md transition-all ${floorMode === 'preset' ? 'bg-slate-600 text-white shadow' : 'text-slate-400 hover:text-white'}`}
                      >
                        Select
                      </button>
                    </div>
                  </div>

                  {floorMode === 'upload' ? (
                    <FileUpload
                      label=""
                      subLabel="Upload Floor Texture"
                      previewUrl={floorImage?.preview || null}
                      onFileSelect={handleFloorSelect}
                      onClear={() => setFloorImage(null)}
                    />
                  ) : (
                    <div className="grid grid-cols-2 gap-3">
                      <label className={`
                        cursor-pointer border-2 rounded-xl p-3 flex flex-col items-center justify-center gap-2 transition-all
                        ${floorPreset === 'deco-tile' ? 'border-purple-500 bg-purple-500/10' : 'border-slate-700 bg-slate-800 hover:border-slate-600'}
                      `}>
                        <input 
                          type="radio" 
                          name="floorPreset" 
                          value="deco-tile" 
                          checked={floorPreset === 'deco-tile'}
                          onChange={() => setFloorPreset('deco-tile')}
                          className="hidden" 
                        />
                        <div className="w-8 h-8 rounded bg-gradient-to-br from-slate-400 to-slate-600 opacity-80"></div>
                        <span className="text-xs font-medium text-slate-300">Deco Tile<br/>(데코타일)</span>
                      </label>

                      <label className={`
                        cursor-pointer border-2 rounded-xl p-3 flex flex-col items-center justify-center gap-2 transition-all
                        ${floorPreset === 'marble' ? 'border-purple-500 bg-purple-500/10' : 'border-slate-700 bg-slate-800 hover:border-slate-600'}
                      `}>
                        <input 
                          type="radio" 
                          name="floorPreset" 
                          value="marble" 
                          checked={floorPreset === 'marble'}
                          onChange={() => setFloorPreset('marble')}
                          className="hidden" 
                        />
                        <div className="w-8 h-8 rounded bg-gradient-to-br from-white to-slate-300 opacity-80"></div>
                        <span className="text-xs font-medium text-slate-300">Marble<br/>(대리석)</span>
                      </label>
                    </div>
                  )}
                </div>

                {/* Panels Grid */}
                <div>
                  <div className="flex items-center justify-between mb-2">
                    <label className="block text-sm font-medium text-slate-300">Panel Textures (1-11)</label>
                    <button
                      type="button"
                      onClick={handleApplyPanel1ToAll}
                      disabled={!isPanel1Ready}
                      className={`
                        text-xs font-medium px-3 py-1.5 rounded-lg transition-all flex items-center gap-2 border
                        ${isPanel1Ready 
                          ? 'text-emerald-300 bg-emerald-500/10 border-emerald-500/30 hover:bg-emerald-500/20 cursor-pointer active:scale-95' 
                          : 'text-slate-500 bg-slate-800/50 border-slate-700 cursor-not-allowed'}
                      `}
                      title="Copies the texture/preset from Panel 1 to all panels (1-11)"
                    >
                      {isApplied ? (
                         <>
                           <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                           Done! (완료)
                         </>
                      ) : (
                         <>
                           <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                           Apply to All (전체 적용)
                         </>
                      )}
                    </button>
                  </div>
                  <div className="max-h-[500px] overflow-y-auto custom-scrollbar pr-2">
                     <MaterialGrid 
                       panels={panels}
                       onUpdatePanelFile={handlePanelUpdateFile}
                       onRemovePanelFile={handlePanelRemoveFile}
                       onUpdatePanelMode={handlePanelUpdateMode}
                       onUpdatePanelPreset={handlePanelUpdatePreset}
                     />
                  </div>
                </div>
              </div>
            </section>

            {/* Generate Action */}
            <div className="sticky bottom-4 z-10">
               <button
                type="button"
                onClick={handleGenerate}
                disabled={!layoutImage || appState === AppState.GENERATING}
                className={`
                  w-full py-4 px-6 rounded-xl font-bold text-lg shadow-lg transition-all duration-300 transform
                  flex items-center justify-center gap-3
                  ${!layoutImage 
                    ? 'bg-slate-800 text-slate-500 cursor-not-allowed' 
                    : appState === AppState.GENERATING
                      ? 'bg-purple-600 cursor-wait animate-pulse'
                      : 'bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 hover:scale-[1.02] hover:shadow-purple-500/25 text-white'
                  }
                `}
              >
                {appState === AppState.GENERATING ? (
                  <>
                    <svg className="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                      <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Rendering... ({formatTime(elapsedTime)})
                  </>
                ) : (
                  <>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
                    Generate High-Quality Visualization
                  </>
                )}
              </button>
              {errorMessage && (
                <div className="mt-3 p-3 bg-red-500/10 border border-red-500/20 text-red-400 text-sm rounded-lg text-center">
                  {errorMessage}
                </div>
              )}
            </div>
          </div>

          {/* RIGHT COLUMN: Preview */}
          <div className="w-full lg:w-7/12">
            <div className="bg-slate-900 rounded-2xl border border-slate-800 shadow-xl overflow-hidden h-full min-h-[600px] flex flex-col">
              <div className="p-4 border-b border-slate-800 flex justify-between items-center bg-slate-900/50 backdrop-blur">
                <h3 className="font-semibold text-slate-200">High-Res Output (2K)</h3>
                {resultImage && (
                  <button 
                    onClick={handleReset}
                    className="text-xs text-slate-400 hover:text-white underline"
                  >
                    Clear Result
                  </button>
                )}
              </div>
              
              <div className="flex-1 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-slate-950 flex items-center justify-center p-8 relative">
                
                {/* Placeholder / Empty State */}
                {appState === AppState.IDLE && !resultImage && (
                  <div className="text-center opacity-30 select-none">
                     <div className="w-32 h-32 mx-auto mb-6 rounded-full bg-slate-800 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1" strokeLinecap="round" strokeLinejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                     </div>
                     <h3 className="text-2xl font-light text-slate-400 mb-2">Ready to Render</h3>
                     <p className="max-w-xs mx-auto text-slate-600">Upload your elevator layout and materials to generate a 3D perspective view.</p>
                  </div>
                )}

                {/* Loading State Overlay */}
                {appState === AppState.GENERATING && (
                  <div className="absolute inset-0 bg-slate-950/80 backdrop-blur-sm z-20 flex flex-col items-center justify-center">
                     <div className="w-24 h-24 border-4 border-purple-500/30 border-t-purple-500 rounded-full animate-spin mb-6"></div>
                     <p className="text-purple-300 font-medium animate-pulse">Processing High-Quality Geometry...</p>
                     <p className="text-white text-3xl font-mono mt-4 font-bold tracking-wider">{formatTime(elapsedTime)}</p>
                     <p className="text-slate-500 text-sm mt-4">Target Aspect Ratio: {layoutAspectRatio} • 2K Resolution</p>
                  </div>
                )}

                {/* Result */}
                {resultImage && (
                  <div className="relative w-full h-full flex items-center justify-center group">
                    <img 
                      src={resultImage} 
                      alt="Generated Elevator Interior" 
                      className="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl shadow-black/50" 
                    />
                    <div className="absolute bottom-6 right-6 opacity-0 group-hover:opacity-100 transition-opacity">
                      <a 
                        href={resultImage} 
                        download="elevator-render-pro.png"
                        className="bg-white text-slate-900 px-4 py-2 rounded-lg font-bold shadow-lg hover:bg-slate-200 flex items-center gap-2"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download High-Res
                      </a>
                    </div>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  );
};

export default App;
