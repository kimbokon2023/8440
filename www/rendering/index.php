<?php
// rendering/index.php
require_once __DIR__ . '/../bootstrap.php';
if (!isset($root_dir)) $root_dir = '..';

// 1. Guest Redirect Check
if (!isset($_SESSION["userid"]) || empty($_SESSION["userid"])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='$root_dir/login/login_form.php';</script>";
    exit;
}

$apiKeyPath = __DIR__ . '/gemini_api.txt';
$apiKey = '';
if (file_exists($apiKeyPath)) {
    $apiKey = trim(file_get_contents($apiKeyPath));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ElevatorViz Pro</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google GenAI SDK -->
    <script type="importmap">
      {
        "imports": {
          "@google/genai": "https://esm.run/@google/generative-ai"
        }
      }
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #1e293b; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }
        
        /* Range Slider Styling */
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 16px; width: 16px;
            border-radius: 50%;
            background: #ffffff;
            cursor: pointer;
            margin-top: -4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }
        input[type=range]::-webkit-slider-runnable-track {
            height: 8px;
            border-radius: 4px;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-200 selection:bg-purple-500/30 font-sans">
    
    <?php if (empty($apiKey)): ?>
    <div class="min-h-screen flex flex-col items-center justify-center p-4 text-center">
        <div class="w-20 h-20 bg-gradient-to-tr from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-2xl shadow-purple-500/30 mb-8">
           <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="m2 22 1-1h3l9-9"/><path d="M13 6.17V11h4.83"/><path d="M15.19 15.19 18 18"/></svg>
        </div>
        <h1 class="text-4xl font-bold text-white mb-4">ElevatorViz <span class="text-purple-400">Pro</span></h1>
        <p class="text-slate-400 max-w-md mb-8 text-lg">
          API Key not found in <code>gemini_api.txt</code>.<br>
          Please ensure the file exists in the <code>rendering/</code> directory.
        </p>
    </div>
    <?php else: ?>

    <header class="sticky top-0 z-50 bg-slate-950/90 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-[1600px] mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <!-- Logo -->
                <a href="<?= $root_dir ?>/index.php" class="flex items-center gap-2 group">                    
                    <div class="h-6 w-px bg-slate-700 mx-2"></div>
                    <h1 class="text-lg font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-indigo-400">
                        ElevatorViz <span class="text-slate-500 font-normal text-sm ml-1">Pro</span>
                    </h1>
                </a>

                <nav class="hidden xl:flex items-center gap-1">
                    
                    <!-- Home -->
                    <a href="<?= $root_dir ?>/index.php?home=1" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/><path d="m8 3.293 4.712 4.712A4.5 4.5 0 0 0 8.758 15H3.5A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/></svg>
                    </a>
                </nav>
       
        </div>
    </header>    

    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- CONTROLS -->
            <div class="w-full lg:w-5/12 space-y-8">
                
                <!-- Layout -->
                <section class="bg-slate-900 rounded-2xl p-6 border border-slate-800 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-xs font-bold">1</span>
                            Structural Layout
                        </h2>
                        <div class="flex items-center gap-2">
                            <span id="layoutAspectRatioBadge" class="hidden text-xs text-indigo-400 font-mono bg-indigo-900/30 px-2 py-0.5 rounded border border-indigo-500/30">
                                Ratio: <span id="layoutAspectRatioValue">1:1</span>
                            </span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 mb-4">Upload the wireframe or line drawing.</p>
                    
                    <div class="border-2 border-dashed border-slate-700 rounded-xl p-4 transition-colors hover:border-blue-500/50 hover:bg-slate-800/50 group relative bg-slate-950/30 min-h-[12rem] flex flex-col justify-center">
                        <input type="file" id="layoutInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        
                        <div id="layoutPlaceholder" class="flex flex-col items-center justify-center pointer-events-none">
                             <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center mb-3 group-hover:bg-slate-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                             </div>
                             <p class="text-sm font-medium text-slate-300">Drop Layout Plan Here</p>
                        </div>

                        <div id="layoutPreviewContainer" class="hidden absolute inset-0 rounded-xl overflow-hidden bg-slate-900 z-0">
                            <img id="layoutPreviewImage" src="" class="w-full h-full object-contain p-2" />
                            <button id="clearLayoutBtn" class="absolute top-2 right-2 p-1 bg-red-500/80 text-white rounded-full hover:bg-red-600 z-30 pointer-events-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                    </div>
                </section>

                <!-- Materials -->
                <section class="bg-slate-900 rounded-2xl p-6 border border-slate-800 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-600 text-xs font-bold">2</span>
                            Materials & Lighting
                        </h2>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Sliders -->
                        <div class="bg-slate-950/50 p-4 rounded-xl border border-slate-800 space-y-4">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-sm font-medium text-slate-300">Lighting Color (Kelvin)</label>
                                    <span id="lightingTempValueDisplay" class="text-xs font-mono text-amber-300 bg-amber-900/30 px-2 py-0.5 rounded border border-amber-500/30">2000K</span>
                                </div>
                                <input type="range" id="lightingTempSlider" min="2000" max="6500" step="100" value="2000" class="w-full h-2 bg-gradient-to-r from-orange-500 via-yellow-100 to-blue-300 rounded-lg appearance-none cursor-pointer" />
                            </div>
                            <div class="pt-2 border-t border-slate-800">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-sm font-medium text-slate-300">Reflection Intensity</label>
                                    <span id="reflectionValueDisplay" class="text-xs font-mono text-cyan-300 bg-cyan-900/30 px-2 py-0.5 rounded border border-cyan-500/30">50%</span>
                                </div>
                                <input type="range" id="reflectionSlider" min="0" max="100" step="10" value="50" class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer" />
                            </div>
                        </div>

                        <!-- Door -->
                        <!-- Door -->
                        <div class="bg-slate-950/50 p-4 rounded-xl border border-slate-800">
                            <label class="text-sm font-medium text-slate-300 mb-2 block">Entrance Door</label>
                            
                            <div class="border-2 border-dashed border-slate-700 rounded-xl p-4 transition-colors hover:border-blue-500/50 hover:bg-slate-800/50 group relative bg-slate-950/30 h-32 flex flex-col justify-center">
                                <input type="file" id="doorInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                                
                                <div id="doorPlaceholder" class="flex flex-col items-center justify-center pointer-events-none">
                                     <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center mb-2 group-hover:bg-slate-700 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                     </div>
                                     <p class="text-xs font-medium text-slate-300">Drop Door Texture</p>
                                </div>

                                <div id="doorPreviewContainer" class="hidden absolute inset-0 rounded-xl overflow-hidden bg-slate-900 z-0">
                                     <img id="doorPreviewImage" src="" class="w-full h-full object-contain p-2" />
                                     <button id="clearDoorBtn" class="absolute top-1 right-1 p-0.5 bg-red-500 text-white rounded shadow hover:bg-red-600 z-20 pointer-events-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                     </button>
                                </div>
                            </div>
                        </div>

                        <!-- Floor -->
                        <div class="bg-slate-950/50 p-4 rounded-xl border border-slate-800">
                             <div class="flex items-center justify-between mb-3">
                                <label class="text-sm font-medium text-slate-300">Floor</label>
                                <div class="flex bg-slate-800 rounded-lg p-1 border border-slate-700">
                                  <button id="floorModeUpload" class="text-xs px-3 py-1 rounded-md transition-all bg-slate-600 text-white shadow">Image</button>
                                  <button id="floorModePreset" class="text-xs px-3 py-1 rounded-md transition-all text-slate-400 hover:text-white">Select</button>
                                </div>
                             </div>
                             
                             <div id="floorUploadArea" class="block">
                                <div class="border-2 border-dashed border-slate-700 rounded-xl p-4 transition-colors hover:border-blue-500/50 hover:bg-slate-800/50 group relative bg-slate-950/30 h-32 flex flex-col justify-center">
                                    <input type="file" id="floorInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                                    
                                    <div id="floorPlaceholder" class="flex flex-col items-center justify-center pointer-events-none">
                                         <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center mb-2 group-hover:bg-slate-700 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                         </div>
                                         <p class="text-xs font-medium text-slate-300">Drop Floor Texture</p>
                                    </div>

                                    <div id="floorPreviewContainer" class="hidden absolute inset-0 rounded-xl overflow-hidden bg-slate-900 z-0">
                                         <img id="floorPreviewImage" src="" class="w-full h-full object-contain p-2" />
                                         <button id="clearFloorBtn" class="absolute top-1 right-1 p-0.5 bg-red-500 text-white rounded shadow hover:bg-red-600 z-20 pointer-events-auto">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                         </button>
                                    </div>
                                </div>
                             </div>

                             <div id="floorPresetArea" class="hidden grid grid-cols-2 gap-3">
                                <label class="cursor-pointer border-2 border-purple-500 bg-purple-500/10 rounded-xl p-3 flex flex-col items-center justify-center gap-2 transition-all floor-preset-option" data-value="deco-tile">
                                    <input type="radio" name="floorPreset" value="deco-tile" checked class="hidden" />
                                    <div class="w-8 h-8 rounded bg-gradient-to-br from-slate-400 to-slate-600 opacity-80"></div>
                                    <span class="text-xs font-medium text-slate-300 text-center">Deco Tile<br/>(데코타일)</span>
                                </label>
                                <label class="cursor-pointer border-2 border-slate-700 bg-slate-800 rounded-xl p-3 flex flex-col items-center justify-center gap-2 transition-all floor-preset-option hover:border-slate-600" data-value="marble">
                                    <input type="radio" name="floorPreset" value="marble" class="hidden" />
                                    <div class="w-8 h-8 rounded bg-gradient-to-br from-white to-slate-300 opacity-80"></div>
                                    <span class="text-xs font-medium text-slate-300 text-center">Marble<br/>(대리석)</span>
                                </label>
                             </div>
                        </div>

                        <!-- Panels -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-slate-300">Panels (1-11)</label>
                                <button type="button" id="applyPanel1Btn" disabled class="text-xs font-medium px-3 py-1.5 rounded-lg transition-all flex items-center gap-2 border text-slate-500 bg-slate-800/50 border-slate-700 cursor-not-allowed">
                                    Copy Panel 1 to All
                                </button>
                            </div>
                            <div id="panelsContainer" class="max-h-[500px] overflow-y-auto custom-scrollbar pr-2 space-y-2">
                            </div>
                        </div>
                    </div>
                </section>

                <div class="sticky bottom-4 z-10">
                    <button type="button" id="generateBtn" disabled class="w-full py-4 px-6 rounded-xl font-bold text-lg shadow-lg transition-all duration-300 transform flex items-center justify-center gap-3 bg-slate-800 text-slate-500 cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
                        Generate Visualization
                    </button>
                    <div id="errorMessage" class="hidden mt-3 p-3 bg-red-500/10 border border-red-500/20 text-red-400 text-sm rounded-lg text-center"></div>
                </div>
            </div>

            <!-- PREVIEW -->
            <div class="w-full lg:w-7/12">
                <div class="bg-slate-900 rounded-2xl border border-slate-800 shadow-xl overflow-hidden h-full min-h-[600px] flex flex-col">
                    <div class="p-4 border-b border-slate-800 flex justify-between items-center bg-slate-900/50 backdrop-blur">
                        <h3 class="font-semibold text-slate-200">Result (Pro 2K)</h3>
                        <button id="clearResultBtn" class="hidden text-xs text-slate-400 hover:text-white underline">Clear Result</button>
                    </div>
                    
                    <!-- Prompt Debug Area -->
                    <div id="promptDebugContainer" class="hidden p-4 bg-slate-950 border-b border-slate-800">
                        <div class="flex items-center justify-between mb-2">
                             <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Final Prompt (Debug)</label>
                             <button id="copyPromptBtn" class="text-xs bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-2 py-1 rounded transition-colors flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                Copy Not included Images
                             </button>
                        </div>
                        <textarea id="promptDebugText" class="w-full h-48 bg-slate-900 border border-slate-800 rounded p-2 text-xs text-slate-400 font-mono custom-scrollbar resize-none outline-none focus:border-slate-600" readonly></textarea>
                    </div>

                    <div class="flex-1 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-slate-950 flex items-center justify-center p-8 relative overflow-hidden">
                        <!-- Empty State -->
                        <div id="previewEmptyState" class="text-center opacity-30 select-none">
                            <div class="w-32 h-32 mx-auto mb-6 rounded-full bg-slate-800 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-white"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                            </div>
                            <h3 class="text-2xl font-light text-slate-400 mb-2">Ready to Render</h3>
                            <p class="max-w-xs mx-auto text-slate-600">Upload layout and materials.</p>
                        </div>
                        <!-- Loading State -->
                        <div id="loadingOverlay" class="hidden absolute inset-0 bg-slate-950/80 backdrop-blur-sm z-20 flex flex-col items-center justify-center">
                            <div class="w-24 h-24 border-4 border-purple-500/30 border-t-purple-500 rounded-full animate-spin mb-6"></div>
                            <p class="text-purple-300 font-medium animate-pulse">Processing High-Quality Geometry...</p>
                            <p id="timerDisplay" class="text-white text-3xl font-mono mt-4 font-bold tracking-wider">00:00</p>
                        </div>
                        <!-- Result -->
                        <div id="resultContainer" class="hidden relative w-full h-full flex items-center justify-center group">
                            <img id="resultImage" src="" class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl shadow-black/50" />
                            <div class="absolute bottom-6 right-6 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a id="downloadLink" href="#" download="elevator_render.png" class="bg-white text-slate-900 px-4 py-2 rounded-lg font-bold shadow-lg hover:bg-slate-200 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    Download High-Res
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script type="module">
        import { GoogleGenerativeAI } from "@google/genai";

        const state = {
            apiKey: "<?php echo htmlspecialchars($apiKey); ?>",
            layout: { file: null, preview: null, aspectRatio: "1:1" },
            door: { file: null, preview: null },
            floor: { mode: 'upload', file: null, preview: null, preset: 'deco-tile' },
            panels: Array.from({ length: 11 }, (_, i) => ({
                id: (i + 1).toString(), mode: 'upload', file: null, previewUrl: null, presetType: 'hairline', presetColor: 'silver'
            })),
            lightingTemp: 2000,
            reflectionIntensity: 50,
            timerId: null
        };

        // Validate API Key on load
        if (!state.apiKey || state.apiKey.trim() === '') {
            console.error('API Key is missing or empty');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-20 left-1/2 transform -translate-x-1/2 bg-red-500/90 text-white px-6 py-4 rounded-lg shadow-2xl z-50';
            errorDiv.innerHTML = '⚠️ API 키가 없습니다. gemini_api.txt 파일을 확인하세요.';
            document.body.appendChild(errorDiv);
            setTimeout(() => errorDiv.remove(), 5000);
        } else {
            console.log('API Key loaded:', state.apiKey.substring(0, 10) + '...');
        }

        const $ = id => document.getElementById(id);
        const showError = (html) => {
            const el = $('errorMessage');
            if (!el) return;
            el.innerHTML = html;
            el.classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        const formatTime = (secs) => {
            const m = Math.floor(secs / 60).toString().padStart(2, '0');
            const s = (secs % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        };

        const fileToPart = (file) => new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve({
                 inlineData: { data: reader.result.split(',')[1], mimeType: file.type }
            });
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });

        // UI Updates
        function updateLayoutUI() {
            if (state.layout.file) {
                $('layoutPlaceholder').classList.add('hidden');
                $('layoutPreviewContainer').classList.remove('hidden');
                $('layoutPreviewImage').src = state.layout.preview;
                $('layoutAspectRatioBadge').classList.remove('hidden');
                $('layoutAspectRatioValue').textContent = state.layout.aspectRatio;
                $('generateBtn').disabled = false;
                $('generateBtn').classList.remove('bg-slate-800', 'text-slate-500', 'cursor-not-allowed');
                $('generateBtn').classList.add('bg-gradient-to-r', 'from-purple-600', 'to-indigo-600', 'text-white');
            } else {
                $('layoutPlaceholder').classList.remove('hidden');
                $('layoutPreviewContainer').classList.add('hidden');
                $('layoutPreviewImage').src = "";
                $('layoutAspectRatioBadge').classList.add('hidden');
                $('generateBtn').disabled = true;
                $('generateBtn').classList.add('bg-slate-800', 'text-slate-500', 'cursor-not-allowed');
                $('generateBtn').classList.remove('bg-gradient-to-r', 'from-purple-600', 'to-indigo-600', 'text-white');
            }
        }

        function updateDoorUI() {
            if (state.door.file) {
                 $('doorPlaceholder').classList.add('hidden');
                 $('doorPreviewContainer').classList.remove('hidden');
                 $('doorPreviewImage').src = state.door.preview;
            } else {
                 $('doorPlaceholder').classList.remove('hidden');
                 $('doorPreviewContainer').classList.add('hidden');
                 $('doorPreviewImage').src = "";
            }
        }

        function updateFloorUI() {
            if (state.floor.mode === 'upload') {
                $('floorModeUpload').classList.replace('text-slate-400', 'bg-slate-600');
                $('floorModeUpload').classList.replace('hover:text-white', 'text-white');
                $('floorModeUpload').classList.add('shadow');
                $('floorModePreset').classList.replace('bg-slate-600', 'text-slate-400');
                $('floorModePreset').classList.replace('text-white', 'hover:text-white');
                $('floorModePreset').classList.remove('shadow');
                $('floorUploadArea').classList.remove('hidden');
                $('floorPresetArea').classList.add('hidden');
                if (state.floor.file) {
                    $('floorPlaceholder').classList.add('hidden');
                    $('floorPreviewContainer').classList.remove('hidden');
                    $('floorPreviewImage').src = state.floor.preview;
                } else {
                    $('floorPlaceholder').classList.remove('hidden');
                    $('floorPreviewContainer').classList.add('hidden');
                }
            } else {
                $('floorModePreset').classList.replace('text-slate-400', 'bg-slate-600');
                $('floorModePreset').classList.replace('hover:text-white', 'text-white');
                $('floorModePreset').classList.add('shadow');
                $('floorModeUpload').classList.replace('bg-slate-600', 'text-slate-400');
                $('floorModeUpload').classList.replace('text-white', 'hover:text-white');
                $('floorModeUpload').classList.remove('shadow');
                $('floorUploadArea').classList.add('hidden');
                $('floorPresetArea').classList.remove('hidden');
                document.querySelectorAll('.floor-preset-option').forEach(el => {
                    if (el.dataset.value === state.floor.preset) {
                        el.classList.add('border-purple-500', 'bg-purple-500/10');
                        el.classList.remove('border-slate-700', 'bg-slate-800');
                    } else {
                        el.classList.remove('border-purple-500', 'bg-purple-500/10');
                        el.classList.add('border-slate-700', 'bg-slate-800');
                    }
                });
            }
        }

        function renderPanels() {
            const container = $('panelsContainer');
            container.innerHTML = '';
            state.panels.forEach(panel => {
                const div = document.createElement('div');
                div.className = "flex items-center gap-3 p-3 bg-slate-800/50 rounded-lg border border-slate-700";
                let content = `
                    <div class="w-10 text-sm font-bold text-slate-500">#${panel.id}</div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                             <span class="text-xs font-medium text-slate-300">Mode:</span>
                             <div class="flex bg-slate-700 rounded p-0.5">
                                 <button class="text-[10px] px-2 py-0.5 rounded ${panel.mode === 'upload' ? 'bg-slate-500 text-white' : 'text-slate-400'}" onclick="window.setPanelMode('${panel.id}', 'upload')">Img</button>
                                 <button class="text-[10px] px-2 py-0.5 rounded ${panel.mode === 'preset' ? 'bg-slate-500 text-white' : 'text-slate-400'}" onclick="window.setPanelMode('${panel.id}', 'preset')">Set</button>
                             </div>
                        </div>`;
                if (panel.mode === 'upload') {
                    if (panel.file) {
                        content += `
                            <div class="relative w-full h-12 bg-slate-900 rounded overflow-hidden group">
                                <img src="${panel.previewUrl}" class="w-full h-full object-cover opacity-80" />
                                <button onclick="window.removePanelFile('${panel.id}')" class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 text-white text-xs">Remove</button>
                            </div>
                        `;
                    } else {
                        content += `
                            <label 
                                class="block w-full h-12 border border-dashed border-slate-600 rounded flex items-center justify-center cursor-pointer hover:bg-slate-700/50 transition-colors"
                                ondragover="window.handlePanelDragOver(event)"
                                ondragleave="window.handlePanelDragLeave(event)"
                                ondrop="window.handlePanelDrop('${panel.id}', event)"
                            >
                                <span class="text-[10px] text-slate-400 pointer-events-none">+ Upload / Drop</span>
                                <input type="file" class="hidden" accept="image/*" onchange="window.handlePanelUpload('${panel.id}', this)" onclick="this.value=null" />
                            </label>
                        `;
                    }
                } else {
                    // Circular Color Picker & Type Select
                    content += `
                        <div class="flex flex-col gap-2">
                             <!-- Color Circles -->
                             <div class="flex gap-2 justify-center bg-slate-900 p-2 rounded border border-slate-700">
                                ${['silver', 'gold', 'bronze', 'black'].map(c => {
                                    const isSelected = panel.presetColor === c;
                                    const isLight = ['silver', 'gold'].includes(c);
                                    return `
                                    <button 
                                        onclick="window.setPanelPresetAttr('${panel.id}', 'color', '${c}')" 
                                        class="w-6 h-6 rounded-full relative shadow-sm hover:scale-110 transition-transform flex items-center justify-center ${isSelected ? 'ring-2 ring-white ring-offset-2 ring-offset-slate-900' : 'opacity-70 hover:opacity-100'}"
                                        title="${c.charAt(0).toUpperCase() + c.slice(1)}"
                                        style="background: ${getColorGradient(c)}"
                                    >
                                        ${isSelected ? `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="${isLight ? 'text-slate-900' : 'text-white'}"><polyline points="20 6 9 17 4 12"/></svg>` : ''}
                                    </button>
                                `}).join('')}
                             </div>
                             
                             <!-- Type Select -->
                             <select onchange="window.setPanelPresetAttr('${panel.id}', 'type', this.value)" class="w-full bg-slate-900 text-white text-[10px] p-1.5 border border-slate-700 rounded text-center">
                                <option value="hairline" ${panel.presetType === 'hairline' ? 'selected' : ''}>Hairline (Brushed)</option>
                                <option value="mirror" ${panel.presetType === 'mirror' ? 'selected' : ''}>Mirror (Polished)</option>
                                <option value="vibration" ${panel.presetType === 'vibration' ? 'selected' : ''}>Vibration (Swirl)</option>
                                <option value="bead" ${panel.presetType === 'bead' ? 'selected' : ''}>Bead Blast (Matte)</option>
                            </select>
                        </div>
                    `;
                }
                content += `</div>`;
                div.innerHTML = content;
                container.appendChild(div);
            });

            // Update Apply All Button
            const p1 = state.panels[0];
            const p1Ready = (p1.mode === 'upload' && p1.file) || (p1.mode === 'preset');
            const applyBtn = $('applyPanel1Btn');
            if (p1Ready) {
                applyBtn.disabled = false;
                applyBtn.classList.remove('text-slate-500', 'bg-slate-800/50', 'border-slate-700', 'cursor-not-allowed');
                applyBtn.classList.add('text-emerald-300', 'bg-emerald-500/10', 'border-emerald-500/30', 'cursor-pointer');
            } else {
                applyBtn.disabled = true;
                applyBtn.classList.add('text-slate-500', 'bg-slate-800/50', 'border-slate-700', 'cursor-not-allowed');
                applyBtn.classList.remove('text-emerald-300', 'bg-emerald-500/10', 'border-emerald-500/30', 'cursor-pointer');
            }
        }

        function getColorGradient(color) {
            switch(color) {
                case 'silver': return 'linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%)';
                case 'gold': return 'linear-gradient(135deg, #fce38a 0%, #f38181 100%)';
                case 'bronze': return 'linear-gradient(135deg, #d4a373 0%, #8a5a44 100%)';
                case 'black': return 'linear-gradient(135deg, #434343 0%, #000000 100%)';
                default: return '#ccc';
            }
        }


        // Global Handlers
        window.setPanelMode = (id, mode) => {
            state.panels = state.panels.map(p => p.id === id ? { ...p, mode } : p);
            renderPanels();
        };
        // Fix duplicate/broken handler logic
         window.handlePanelUpload = (id, input) => {
            if (input && input.files && input.files[0]) {
                const file = input.files[0];
                state.panels = state.panels.map(p => p.id === id ? { ...p, file, previewUrl: URL.createObjectURL(file) } : p);
                renderPanels();
            }
        };
        
        // Drag and Drop Handlers for Panels
        window.handlePanelDragOver = (e) => {
            e.preventDefault();
            e.stopPropagation();
            e.currentTarget.classList.add('border-blue-500', 'bg-slate-700/80');
        };
        window.handlePanelDragLeave = (e) => {
            e.preventDefault();
            e.stopPropagation();
            e.currentTarget.classList.remove('border-blue-500', 'bg-slate-700/80');
        };
        window.handlePanelDrop = (id, e) => {
            e.preventDefault();
            e.stopPropagation();
            e.currentTarget.classList.remove('border-blue-500', 'bg-slate-700/80');
            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
                 const file = e.dataTransfer.files[0];
                 state.panels = state.panels.map(p => p.id === id ? { ...p, file, previewUrl: URL.createObjectURL(file) } : p);
                 renderPanels();
            }
        };

        window.removePanelFile = (id) => {
            state.panels = state.panels.map(p => p.id === id ? { ...p, file: null, previewUrl: null } : p);
            renderPanels();
        };
        window.setPanelPresetAttr = (id, attr, value) => {
             state.panels = state.panels.map(p => {
                 if (p.id === id) { if (attr === 'color') p.presetColor = value; else p.presetType = value; }
                 return p;
             });
             renderPanels();
        };

        // Listeners
        $('layoutInput').addEventListener('change', (e) => {
            if (e.target.files[0]) {
                const file = e.target.files[0];
                const img = new Image();
                img.onload = () => {
                    const ratio = img.width / img.height;
                    const supported = [ {id:"1:1",v:1.0}, {id:"3:4",v:0.75}, {id:"4:3",v:1.333}, {id:"9:16",v:0.5625}, {id:"16:9",v:1.777} ];
                    state.layout.aspectRatio = supported.reduce((p, c) => Math.abs(c.v - ratio) < Math.abs(p.v - ratio) ? c : p).id;
                    state.layout.file = file;
                    state.layout.preview = URL.createObjectURL(file);
                    updateLayoutUI();
                };
                img.src = URL.createObjectURL(file);
            }
        });
        $('clearLayoutBtn').addEventListener('click', () => { 
            state.layout = { file: null, preview: null, aspectRatio: "1:1" }; 
            $('layoutInput').value = ''; 
            updateLayoutUI(); 
        });

        $('lightingTempSlider').addEventListener('input', (e) => {
            state.lightingTemp = e.target.value;
            $('lightingTempValueDisplay').textContent = state.lightingTemp + "K";
        });
        $('reflectionSlider').addEventListener('input', (e) => {
            state.reflectionIntensity = parseInt(e.target.value);
            $('reflectionValueDisplay').textContent = state.reflectionIntensity + "%";
        });

        $('doorInput').addEventListener('change', (e) => {
            if (e.target.files[0]) {
                state.door.file = e.target.files[0];
                state.door.preview = URL.createObjectURL(state.door.file);
                updateDoorUI();
            }
        });
        $('clearDoorBtn').addEventListener('click', () => { 
            state.door = { file: null, preview: null }; 
            $('doorInput').value = ''; 
            updateDoorUI(); 
        });

        $('floorModeUpload').addEventListener('click', () => { state.floor.mode = 'upload'; updateFloorUI(); });
        $('floorModePreset').addEventListener('click', () => { state.floor.mode = 'preset'; updateFloorUI(); });
        $('floorInput').addEventListener('change', (e) => {
            if (e.target.files[0]) { state.floor.file = e.target.files[0]; state.floor.preview = URL.createObjectURL(state.floor.file); updateFloorUI(); }
        });
        $('clearFloorBtn').addEventListener('click', () => { state.floor.file = null; $('floorInput').value=''; updateFloorUI(); });
        document.querySelectorAll('input[name="floorPreset"]').forEach(radio => radio.addEventListener('change', (e) => { if (e.target.checked) { state.floor.preset = e.target.value; updateFloorUI(); } }));

        $('applyPanel1Btn').addEventListener('click', () => {
            const p1 = state.panels[0];
            state.panels = state.panels.map(p => ({ ...p, mode: p1.mode, file: p1.file, previewUrl: p1.previewUrl, presetType: p1.presetType, presetColor: p1.presetColor }));
            renderPanels();
        });

        $('clearResultBtn').addEventListener('click', () => {
            $('resultContainer').classList.add('hidden');
            $('previewEmptyState').classList.remove('hidden');
            $('clearResultBtn').classList.add('hidden');
            $('promptDebugContainer').classList.add('hidden');
        });

        $('copyPromptBtn').addEventListener('click', () => {
            const textToCopy = $('promptDebugText').value;
             navigator.clipboard.writeText(textToCopy).then(() => {
                const originalText = $('copyPromptBtn').innerHTML;
                $('copyPromptBtn').innerHTML = `<span class="text-green-400">Copied!</span>`;
                setTimeout(() => {
                    $('copyPromptBtn').innerHTML = originalText;
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        });

        // Generation
        $('generateBtn').addEventListener('click', async () => {
            if (!state.layout.file) return;
            state.isGenerating = true;
            
            // UI Updates for Generation Start
            const btn = $('generateBtn');
            const originalBtnContent = btn.innerHTML;
            btn.disabled = true;
            btn.classList.add('cursor-not-allowed', 'opacity-75');
            
            $('loadingOverlay').classList.remove('hidden');
            $('errorMessage').classList.add('hidden');
            $('promptDebugContainer').classList.add('hidden');
            
            let seconds = 0;
            // Update button text immediately
            btn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generating... 00:00`;
            
            state.timerId = setInterval(() => { 
                seconds++; 
                const timeStr = formatTime(seconds);
                $('timerDisplay').textContent = timeStr;
                btn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generating... ${timeStr}`;
            }, 1000);

            try {
                // CRITICAL: gemini-3-pro-image-preview requires PAID tier
                // This model is specifically for high-quality image generation
                const genAI = new GoogleGenerativeAI(state.apiKey);
        // Helper to generate reflection description based on intensity (0-100)
        function getReflectionContext(intensity, type) {
            if (type === 'mirror') {
               if (intensity < 30) return `Use a 'Foggy/Antique Mirror' finish. The reflections should be very blurred and subtle (Intensity: ${intensity}%). Do NOT make it perfectly clear.`;
               if (intensity < 70) return `Use a 'Standard Polished' finish. Reflections are visible but slightly softened (Intensity: ${intensity}%).`;
               return `Use a 'Perfect Chrome/Mirror' finish. Reflections should be extremely sharp, clear, and high-contrast (Intensity: ${intensity}%).`;
            }

            if (intensity < 20) return `Finish: Matte/Flat. Minimal to no specular highlights. (Intensity: ${intensity}%)`;
            if (intensity > 80) return `Finish: High Gloss / Wet Look. Strong specular highlights and sharp reflections. (Intensity: ${intensity}%)`;
            
            return `Finish: Standard Architectural Satin/Semi-Gloss. Balanced reflections. (Intensity: ${intensity}%)`;
        }

                // REQUIRED: gemini-3-pro-image-preview for 3.0 version image generation
                // NOTE: This model requires a PAID Google Cloud billing account
                const model = genAI.getGenerativeModel({ model: "gemini-3-pro-image-preview" });

                const parts = [];
                // 1. System/Context Instruction - Highly detailed
                parts.push({
                    text: `You are an expert 3D architectural visualizer. 
                    Your task is to generate a high-quality, photorealistic rendering of an elevator interior.
                    
                    PRIMARY OBJECTIVE: PRESERVE GEOMETRY
                    You will be provided with a 'REFERENCE LAYOUT STRUCTURE' image. 
                    This image is the absolute mask and wireframe for the scene. 
                    You must NOT change the aspect ratio, the perspective lines, or the relative sizes of the panels defined in this layout.
                    Even if you have generated images before, disregard them. Treat this layout image as the ONLY truth for geometry.

                    CRITICAL: PANEL SEPARATION AND SEAMS
                    The layout consists of 11 distinct vertical panels. The lines separating these panels are physical gaps/seams.
                    - You MUST output these vertical division lines clearly. 
                    - Do NOT allow separate panel textures to bleed across these lines.
                    - Do NOT obscure the panel gaps with patterns. Each panel is an individual object.

                    CRITICAL: MATERIAL SCALING AND REALISM
                    - Apply materials at a 1:1 SCALE based on the provided sample.
                    - STRICTLY FORBIDDEN: Do NOT magnify, stretch, or zoom in on the pattern image.
                    - If the provided image is a pattern or texture, you must TILE it to cover the panel surface.
                    - The density of the pattern in the output must match the density in the provided source image.
                    - Think of the input image as a 50cm x 50cm sample. Do not stretch this small sample to cover a 2m high door. Repeat it.
                    CRITICAL: FLAT ETCHING AND PATTERNS
                    - If a panel has a pattern, design, or "Etching" image:
                    - Render it as a FLAT surface treatment (like laser etching, silk-screen printing, or a decal).
                    - Do NOT make the pattern 3D, embossed, or protruding. It must NOT have distinct height or significant bump mapping.
                    - The surface feel should remain smooth. The pattern is visual only, like a drawing on a sheet, not a physical molding.

                    CRITICAL: EXACT PANEL-MATERIAL MAPPING
                    - You will receive inputs labeled 'MATERIAL B1 (Panel 1)', 'MATERIAL B2 (Panel 2)', etc.
                    - You MUST apply the image/description of 'MATERIAL Bx' specifically to 'Panel x' in the layout.
                    - Do NOT mix up the panels. If Panel 5 has an etching image, ONLY Panel 5 should show that etching.
                    `
                });

                // 2. The Layout Image
                parts.push({text: "REFERENCE LAYOUT STRUCTURE (GROUND TRUTH):"});
                parts.push(await fileToPart(state.layout.file));

                if (state.door.file) {
                    parts.push({text: "MATERIAL A (Use for Main Entrance Doors):"});
                    parts.push(await fileToPart(state.door.file));
                }

                if (state.floor.mode === 'upload' && state.floor.file) {
                    parts.push({text: "MATERIAL C (Floor):"});
                    parts.push(await fileToPart(state.floor.file));
                } else {
                     let floorDesc = `FLOOR MATERIAL: ${state.floor.preset}`;
                     if (state.floor.preset === 'marble') {
                         floorDesc = "FLOOR MATERIAL: High-gloss luxury MARBLE STONE. White/Grey veining. Reflective polished surface. DISTINCT from the metal doors.";
                     } else if (state.floor.preset === 'deco') {
                         floorDesc = "FLOOR MATERIAL: Standard architectural DECO-TILE. Matte/Satin finish. Square tiling pattern. DISTINCT from the metal doors.";
                     }
                     // Explicit separation from Door material
                     if (state.door.file) {
                         floorDesc += " [CRITICAL: Do NOT use 'MATERIAL A (Door)' for the floor. The floor must use the specific material described here.]";
                     }
                     parts.push({text: floorDesc});
                }

                let matIndex = 1;
                for (const p of state.panels) {
                    let posContext = "";
                    // Explicitly define positions for Panel 1 and 11 as requested
                    // p.id is a string ("1", "11"), so use string comparison
                    if (p.id === '1') {
                        posContext = " [IMPORTANT: This is the panel immediately to the RIGHT of the central elevator doors (Front Wall Right Return/COP area).] ";
                    } else if (p.id === '11') {
                        posContext = " [IMPORTANT: This is the panel immediately to the LEFT of the central elevator doors (Front Wall Left Return).] ";
                    }

                    if (p.mode === 'upload' && p.file) {
                        parts.push({text: `MATERIAL B${matIndex} (Panel ${p.id}):${posContext}`});
                        parts.push(await fileToPart(p.file));
                        matIndex++;
                    } else if (p.mode === 'preset') {
                        const reflectionDesc = getReflectionContext(state.reflectionIntensity, p.presetType);
                        parts.push({text: `MATERIAL B${matIndex} (Panel ${p.id}):${posContext} Color ${p.presetColor}. Type ${p.presetType}. ${reflectionDesc}`});
                        matIndex++;
                    }
                }

                const reflectionGlobal = getReflectionContext(state.reflectionIntensity, 'standard');
                parts.push({text: `GENERATE. Lighting Temperature: ${state.lightingTemp}K. Global Reflection Style: ${reflectionGlobal}. Output High-Res 3D Render.`});
                
                // ⚠️ CRITICAL: STRUCTURE MUST NOT BE MODIFIED ⚠️
                parts.push({text: `
⚠️⚠️⚠️ ABSOLUTE STRUCTURE REQUIREMENT - DO NOT MODIFY ⚠️⚠️⚠️

CRITICAL WARNING: The structure provided in "REFERENCE LAYOUT STRUCTURE (GROUND TRUTH)" is ABSOLUTE and MUST NOT be changed, modified, or transformed in any way.

YOU MUST:
- Preserve the EXACT layout structure from the reference image
- Maintain the EXACT positioning of all panels, doors, and elements
- Keep the EXACT proportions and spatial relationships
- Do NOT alter, rotate, resize, or reposition any structural elements
- Do NOT add or remove any structural components
- Do NOT change the architectural layout

ONLY apply materials, textures, colors, and lighting to the EXISTING structure.
The structure itself is GROUND TRUTH and is ABSOLUTELY NON-NEGOTIABLE.

ANY modification to the structure is STRICTLY FORBIDDEN.
The structure must remain EXACTLY as shown in the reference layout image.

⚠️⚠️⚠️ STRUCTURE MODIFICATION IS ABSOLUTELY PROHIBITED ⚠️⚠️⚠️
                `});

                // --- DEBUG PROMPT LOGGING ---
                // Create a readable text version of the prompt
                let debugText = "";
                parts.forEach(p => {
                    if (p.text) {
                        debugText += p.text.trim() + "\n\n";
                    } else if (p.inlineData) {
                        debugText += `[IMAGE DATA: ${p.inlineData.mimeType}]\n\n`;
                    }
                });
                
                console.log("FINAL GEMINI PROMPT PARTS:", parts);
                $('promptDebugText').value = debugText;
                $('promptDebugContainer').classList.remove('hidden');
                // -----------------------------

                // Retry logic with exponential backoff for quota errors
                let result;
                let retryCount = 0;
                const maxRetries = 3;
                const baseDelay = 2000; // 2 seconds
                
                while (retryCount <= maxRetries) {
                    try {
                        result = await model.generateContent({
                             contents: [{ role: "user", parts: parts }],
                             generationConfig: {
                                 imageConfig: {
                                      aspectRatio: state.layout.aspectRatio,
                                      imageSize: "2K"
                                 }
                             }
                        });
                        break; // Success, exit retry loop
                    } catch (retryError) {
                        // Check if it's a quota/rate limit error (429)
                        // 429 can appear in message, status code, or response
                        const is429Error = (
                            (retryError.message && retryError.message.includes('429')) ||
                            (retryError.status === 429) ||
                            (retryError.response && retryError.response.status === 429) ||
                            (retryError.message && retryError.message.includes('quota')) ||
                            (retryError.message && retryError.message.includes('free_tier'))
                        );
                        
                        if (is429Error && retryCount < maxRetries) {
                            retryCount++;
                            const delay = baseDelay * Math.pow(2, retryCount - 1); // Exponential backoff
                            const retryDelay = Math.min(delay, 60000); // Max 60 seconds
                            
                            // Extract retry delay from error if available
                            let extractedDelay = retryDelay;
                            try {
                                const errorMatch = retryError.message.match(/Please retry in ([\d.]+)s/);
                                if (errorMatch) {
                                    extractedDelay = parseFloat(errorMatch[1]) * 1000;
                                }
                            } catch (e) {}
                            
                            $('timerDisplay').textContent = `Retrying in ${Math.ceil(extractedDelay / 1000)}s...`;
                            await new Promise(resolve => setTimeout(resolve, extractedDelay));
                            continue;
                        }
                        throw retryError; // Re-throw if not a retryable error or max retries reached
                    }
                }

                // Parse Result - Expecting inline image
                // Parse Result - Expecting inline image
                const response = result.response;
                const candid = response.candidates ? response.candidates[0] : null;
                
                let detailedError = "";
                
                // Check Prompt Feedback (Safety)
                if (response.promptFeedback) {
                     if (response.promptFeedback.blockReason) {
                         detailedError += `Prompt Blocked: ${response.promptFeedback.blockReason}\n`;
                     }
                }

                // Check Candidate Finish Reason
                if (candid) {
                    if (candid.finishReason && candid.finishReason !== "STOP") {
                        detailedError += `Generation Stopped: ${candid.finishReason}\n`;
                    }
                    if (candid.safetyRatings) {
                         const highRisks = candid.safetyRatings.filter(r => r.probability === "HIGH" || r.probability === "MEDIUM");
                         if (highRisks.length > 0) {
                             detailedError += `Safety Filter Triggered: ${highRisks.map(r => r.category).join(", ")}\n`;
                         }
                    }
                }

                let imgSrc = null;
                if (candid && candid.content && candid.content.parts) {
                    for (const p of candid.content.parts) {
                        if (p.inlineData) {
                            imgSrc = `data:${p.inlineData.mimeType};base64,${p.inlineData.data}`;
                            break;
                        }
                    }
                }

                if (imgSrc) {
                    $('resultImage').src = imgSrc;
                    
                    // Generate timestamp for filename: panel_YYYYMMDDHHMMSS.png
                    const now = new Date();
                    const timestamp = now.getFullYear() +
                        String(now.getMonth() + 1).padStart(2, '0') +
                        String(now.getDate()).padStart(2, '0') +
                        String(now.getHours()).padStart(2, '0') +
                        String(now.getMinutes()).padStart(2, '0') +
                        String(now.getSeconds()).padStart(2, '0');

                    $('downloadLink').href = imgSrc;
                    $('downloadLink').download = `panel_${timestamp}.png`;

                    $('resultContainer').classList.remove('hidden');
                    $('previewEmptyState').classList.add('hidden');
                    $('clearResultBtn').classList.remove('hidden');
                } else {
                    let errMsg = "No image generated.";
                    if (detailedError) errMsg += "\n" + detailedError;
                    throw new Error(errMsg);
                }

            } catch (e) {
                console.error('Generation Error:', e);
                let msgHtml = `<div class="space-y-2 text-sm text-left">`;
                
                // Check for quota/free tier errors (429) - requires paid plan
                // 429 can appear in multiple places: message, status, response.status
                const isQuotaError = (
                    (e.message && (
                        e.message.includes('free_tier') || 
                        e.message.includes('quota') || 
                        e.message.includes('429') ||
                        e.message.includes('exceeded your current quota') ||
                        e.message.includes('Too Many Requests')
                    )) ||
                    (e.status === 429) ||
                    (e.response && e.response.status === 429) ||
                    (e.code === 429)
                );
                
                if (isQuotaError) {
                    msgHtml += `<div class="font-semibold text-red-300 mb-2">❌ 쿼터 초과 또는 무료 티어 제한 (HTTP 429)</div>`;
                    msgHtml += `<div class="text-slate-200 mb-3">gemini-3-pro-image-preview 모델은 <strong class="text-yellow-300">유료 플랜</strong>이 필요합니다.</div>`;
                    msgHtml += `<div class="bg-slate-800/50 p-3 rounded-lg border border-slate-700 mb-3">`;
                    msgHtml += `<div class="font-medium text-slate-300 mb-2">해결 방법:</div>`;
                    msgHtml += `<ul class="list-disc list-inside text-slate-300 space-y-1 text-xs">`;
                    msgHtml += `<li>Google Cloud Console에서 결제(유료) 계정 활성화</li>`;
                    msgHtml += `<li>API 키가 유료 프로젝트(번호: 597717139064)에 연결되어 있는지 확인</li>`;
                    msgHtml += `<li>Gemini API에 유료 플랜이 적용되었는지 확인</li>`;
                    msgHtml += `<li>결제 적용 후 아래 "다시 요청하기" 버튼으로 재시도</li>`;
                    msgHtml += `</ul>`;
                    msgHtml += `</div>`;
                    msgHtml += `<a class="text-indigo-300 underline hover:text-indigo-200" target="_blank" rel="noreferrer" href="https://ai.google.dev/pricing">💰 가격 정보 보기</a>`;
                } else {
                    msgHtml += `<div class="font-semibold text-red-300">실패: ${e.message || '알 수 없는 오류'}</div>`;
                }
                
                // Show HTTP status code if available
                const statusCode = e.status || (e.response && e.response.status) || (e.code);
                if (statusCode) {
                    msgHtml += `<div class="text-slate-400 text-xs mt-2">HTTP Status: ${statusCode}</div>`;
                }

                msgHtml += `<div class="pt-2">`;
                msgHtml += `<button id="retryAfterError" class="mt-2 px-3 py-2 bg-indigo-600 text-white rounded-lg text-xs hover:bg-indigo-500">다시 요청하기</button>`;
                msgHtml += `</div>`;
                msgHtml += `</div>`;
                
                showError(msgHtml);

                // Wire up retry button to simply hide error and let user click generate again
                setTimeout(() => {
                    const retryBtn = document.getElementById('retryAfterError');
                    if (retryBtn) {
                        retryBtn.onclick = () => {
                            $('errorMessage').classList.add('hidden');
                            $('errorMessage').innerHTML = '';
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        };
                    }
                }, 50);
            } finally {
                clearInterval(state.timerId);
                state.isGenerating = false;
                $('loadingOverlay').classList.add('hidden');
                
                // Restore button state
                const btn = $('generateBtn');
                btn.disabled = false;
                btn.classList.remove('cursor-not-allowed', 'opacity-75');
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg> Generate Visualization`;
            }
        });

        renderPanels();
        updateLayoutUI();
        updateFloorUI();
    </script>
    <?php endif; ?>
</body>
</html>
