import React, { useRef } from 'react';

interface FileUploadProps {
  label: string;
  subLabel?: string;
  accept?: string;
  previewUrl: string | null;
  onFileSelect: (file: File) => void;
  onClear: () => void;
  className?: string;
}

const FileUpload: React.FC<FileUploadProps> = ({
  label,
  subLabel,
  accept = "image/*",
  previewUrl,
  onFileSelect,
  onClear,
  className = ""
}) => {
  const fileInputRef = useRef<HTMLInputElement>(null);

  const handleDivClick = () => {
    fileInputRef.current?.click();
  };

  const handleFileChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (file) {
      onFileSelect(file);
    }
    // Reset value so same file can be selected again if needed
    event.target.value = '';
  };

  return (
    <div className={`flex flex-col gap-2 ${className}`}>
      <span className="text-sm font-medium text-slate-300">{label}</span>
      
      <div 
        className={`
          relative group cursor-pointer border-2 border-dashed rounded-xl transition-all duration-300 overflow-hidden
          ${previewUrl ? 'border-emerald-500/50 bg-slate-800' : 'border-slate-600 hover:border-blue-400 hover:bg-slate-800/50'}
          h-32 flex items-center justify-center
        `}
        onClick={handleDivClick}
      >
        <input 
          type="file" 
          ref={fileInputRef} 
          className="hidden" 
          accept={accept} 
          onChange={handleFileChange}
        />

        {previewUrl ? (
          <>
            <img 
              src={previewUrl} 
              alt="Preview" 
              className="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity" 
            />
            <button 
              onClick={(e) => { e.stopPropagation(); onClear(); }}
              className="absolute top-1 right-1 bg-red-500/80 hover:bg-red-500 text-white rounded-full p-1 shadow-lg backdrop-blur-sm transition-transform hover:scale-110"
              title="Remove image"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            <div className="absolute bottom-0 left-0 right-0 bg-black/60 p-1 text-xs text-center text-white truncate">
              Change Image
            </div>
          </>
        ) : (
          <div className="text-center p-4">
            <div className="mx-auto w-8 h-8 mb-2 text-slate-400 group-hover:text-blue-400 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            </div>
            <p className="text-xs text-slate-400 font-medium">{subLabel || "Click to upload"}</p>
          </div>
        )}
      </div>
    </div>
  );
};

export default FileUpload;