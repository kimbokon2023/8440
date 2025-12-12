
import React from 'react';
import { MaterialSlot, MaterialMode, PresetType, PresetColor } from '../types';
import FileUpload from './FileUpload';

interface MaterialGridProps {
  panels: MaterialSlot[];
  onUpdatePanelFile: (id: string, file: File) => void;
  onRemovePanelFile: (id: string) => void;
  onUpdatePanelMode: (id: string, mode: MaterialMode) => void;
  onUpdatePanelPreset: (id: string, type: PresetType, color: PresetColor) => void;
}

const MaterialGrid: React.FC<MaterialGridProps> = ({ 
  panels, 
  onUpdatePanelFile, 
  onRemovePanelFile,
  onUpdatePanelMode,
  onUpdatePanelPreset
}) => {
  
  const getPresetPreviewStyle = (type: PresetType, color: PresetColor) => {
    let bg = '';
    // Color base
    switch (color) {
      case 'silver': bg = 'linear-gradient(135deg, #e2e8f0 0%, #94a3b8 100%)'; break;
      case 'gold': bg = 'linear-gradient(135deg, #fcd34d 0%, #d97706 100%)'; break;
      case 'bronze': bg = 'linear-gradient(135deg, #fdba74 0%, #9a3412 100%)'; break;
      case 'black': bg = 'linear-gradient(135deg, #475569 0%, #0f172a 100%)'; break;
    }
    
    // Texture overlay styles
    const styles: React.CSSProperties = { background: bg };

    // Overlay logic based on type
    if (type === 'hairline') {
       styles.backgroundImage = `${bg}, repeating-linear-gradient(90deg, transparent, transparent 1px, rgba(0,0,0,0.1) 1px, rgba(0,0,0,0.1) 2px)`;
    } else if (type === 'mirror') {
       styles.backgroundImage = `${bg}, linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.4) 45%, transparent 50%)`;
    } else if (type === 'vibration') {
       // Simulation of non-directional scratches (noise-like)
       styles.backgroundImage = `${bg}, radial-gradient(circle at 50% 50%, transparent 20%, rgba(0,0,0,0.05) 21%, transparent 22%), radial-gradient(circle at 20% 80%, transparent 20%, rgba(255,255,255,0.1) 21%, transparent 22%)`;
    } else if (type === 'bead') {
       // Matte/Frosted look
       styles.filter = 'contrast(0.9) brightness(1.05)';
    }

    return styles;
  };

  return (
    // Updated grid-cols to 3 for larger screens (removed lg:grid-cols-4)
    <div className="grid grid-cols-2 md:grid-cols-3 gap-4 p-4 bg-slate-900/50 rounded-xl border border-slate-700/50">
      {panels.map((panel) => (
        <div key={panel.id} className="bg-slate-800/50 rounded-xl border border-slate-700 p-2 flex flex-col gap-2 transition-all hover:bg-slate-800">
           <div className="flex justify-between items-center px-1">
             <span className="text-xs font-medium text-slate-400">{panel.label}</span>
             <div className="flex bg-slate-900 rounded-md p-0.5 border border-slate-700">
                <button
                  onClick={() => onUpdatePanelMode(panel.id, 'upload')}
                  className={`text-[10px] px-1.5 py-0.5 rounded transition-all ${panel.mode === 'upload' ? 'bg-slate-600 text-white shadow' : 'text-slate-500 hover:text-slate-300'}`}
                >
                  Img
                </button>
                <button
                  onClick={() => onUpdatePanelMode(panel.id, 'preset')}
                  className={`text-[10px] px-1.5 py-0.5 rounded transition-all ${panel.mode === 'preset' ? 'bg-slate-600 text-white shadow' : 'text-slate-500 hover:text-slate-300'}`}
                >
                  Set
                </button>
             </div>
           </div>

          {panel.mode === 'upload' ? (
            <FileUpload
              label=""
              subLabel="Upload"
              previewUrl={panel.previewUrl}
              onFileSelect={(file) => onUpdatePanelFile(panel.id, file)}
              onClear={() => onRemovePanelFile(panel.id)}
              className="w-full"
            />
          ) : (
            <div className="flex flex-col gap-2 h-36 justify-center">
              {/* Preview Box */}
              <div 
                className="w-full h-10 rounded-lg border border-slate-600 shadow-inner mb-1 relative overflow-hidden transition-all duration-300"
                style={getPresetPreviewStyle(panel.presetType, panel.presetColor)}
              >
                {/* Visual gloss for Mirror */}
                {panel.presetType === 'mirror' && (
                  <div className="absolute inset-0 bg-gradient-to-tr from-transparent via-white/30 to-transparent translate-x-[-100%] animate-[shimmer_2s_infinite]"></div>
                )}
                {/* Noise for Bead */}
                {panel.presetType === 'bead' && (
                  <div className="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-30"></div>
                )}
              </div>

              {/* Color Select */}
              <div className="flex justify-between gap-1 px-1 mb-1">
                 {(['silver', 'gold', 'bronze', 'black'] as PresetColor[]).map((c) => (
                   <button
                     key={c}
                     onClick={() => onUpdatePanelPreset(panel.id, panel.presetType, c)}
                     className={`
                       w-6 h-6 rounded-full border-2 transition-transform hover:scale-110
                       ${panel.presetColor === c ? 'border-white ring-1 ring-purple-500/50 shadow-md' : 'border-transparent opacity-60 hover:opacity-100'}
                     `}
                     style={{ 
                       background: c === 'silver' ? 'linear-gradient(135deg, #e5e7eb, #9ca3af)' : 
                                   c === 'gold' ? 'linear-gradient(135deg, #fbbf24, #d97706)' : 
                                   c === 'bronze' ? 'linear-gradient(135deg, #d97706, #92400e)' : 
                                   'linear-gradient(135deg, #374151, #111827)'
                     }}
                     title={c.charAt(0).toUpperCase() + c.slice(1)}
                   />
                 ))}
              </div>

              {/* Type Select Grid */}
              <div className="grid grid-cols-2 gap-1 px-1">
                 {(['hairline', 'mirror', 'vibration', 'bead'] as PresetType[]).map((t) => (
                   <button
                     key={t}
                     onClick={() => onUpdatePanelPreset(panel.id, t, panel.presetColor)}
                     className={`
                       text-[9px] py-1 rounded border transition-colors truncate
                       ${panel.presetType === t ? 'bg-slate-600 border-slate-500 text-white' : 'border-slate-700 text-slate-500 hover:border-slate-600 hover:text-slate-300'}
                     `}
                     title={t.charAt(0).toUpperCase() + t.slice(1)}
                   >
                     {t === 'hairline' ? 'Hairline' : t === 'mirror' ? 'Mirror' : t === 'vibration' ? 'Vibration' : 'Bead Blast'}
                   </button>
                 ))}
              </div>
            </div>
          )}
        </div>
      ))}
    </div>
  );
};

export default MaterialGrid;
