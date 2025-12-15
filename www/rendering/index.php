<?php
// rendering/index.php
require_once __DIR__ . '/../bootstrap.php';
if (!isset($root_dir)) $root_dir = '..';

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
          <code>gemini_api.txt</code> 파일에서 API 키를 찾을 수 없습니다.<br>
          <code>rendering/</code> 디렉토리에 파일이 존재하는지 확인해주세요.
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
                            구조 레이아웃 (Structural Layout)
                        </h2>
                        <div class="flex items-center gap-2">
                            <span id="layoutAspectRatioBadge" class="hidden text-xs text-indigo-400 font-mono bg-indigo-900/30 px-2 py-0.5 rounded border border-indigo-500/30">
                                Ratio: <span id="layoutAspectRatioValue">1:1</span>
                            </span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 mb-4">와이어프레임 또는 라인 드로잉을 업로드하세요.</p>
                    
                    <div class="border-2 border-dashed border-slate-700 rounded-xl p-4 transition-colors hover:border-blue-500/50 hover:bg-slate-800/50 group relative bg-slate-950/30 min-h-[12rem] flex flex-col justify-center">
                        <input type="file" id="layoutInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        
                        <div id="layoutPlaceholder" class="flex flex-col items-center justify-center pointer-events-none">
                             <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center mb-3 group-hover:bg-slate-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                             </div>
                             <p class="text-sm font-medium text-slate-300">구조 도면을 여기에 놓으세요</p>
                        </div>

                        <div id="layoutPreviewContainer" class="hidden absolute inset-0 rounded-xl overflow-hidden bg-slate-900 z-0">
                            <img id="layoutPreviewImage" src="" class="w-full h-full object-contain p-2" />
                            <button id="clearLayoutBtn" class="absolute top-2 right-2 p-1 bg-red-500/80 text-white rounded-full hover:bg-red-600 z-30 pointer-events-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                    </div>
                </section>

                <!-- Panel Number Guide (Optional) -->
                <section class="bg-slate-900 rounded-2xl p-6 border border-slate-800 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-semibold text-slate-400 flex items-center gap-2">
                            <span class="flex items-center justify-center w-5 h-5 rounded-full bg-slate-700 text-[10px] font-bold">Ref</span>
                            패널 번호 안내도 (선택사항)
                        </h2>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">패널 번호가 적힌 안내도가 있다면 업로드하세요. AI가 위치를 더 정확히 인식합니다.</p>
                    
                    <div class="border-2 border-dashed border-slate-700 rounded-xl p-4 transition-colors hover:border-blue-500/50 hover:bg-slate-800/50 group relative bg-slate-950/30 h-32 flex flex-col justify-center">
                        <input type="file" id="guideInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        
                        <div id="guidePlaceholder" class="flex flex-col items-center justify-center pointer-events-none">
                             <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center mb-2 group-hover:bg-slate-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                             </div>
                             <p class="text-xs font-medium text-slate-300">안내도 이미지 업로드</p>
                        </div>

                        <div id="guidePreviewContainer" class="hidden absolute inset-0 rounded-xl overflow-hidden bg-slate-900 z-0">
                            <img id="guidePreviewImage" src="" class="w-full h-full object-contain p-2" />
                            <button id="clearGuideBtn" class="absolute top-2 right-2 p-1 bg-red-500/80 text-white rounded-full hover:bg-red-600 z-30 pointer-events-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                    </div>
                </section>

                <!-- Materials -->
                <section class="bg-slate-900 rounded-2xl p-6 border border-slate-800 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-600 text-xs font-bold">2</span>
                            재질 및 조명 (Materials & Lighting)
                        </h2>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Sliders -->
                        <div class="bg-slate-950/50 p-4 rounded-xl border border-slate-800 space-y-4">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-sm font-medium text-slate-300">조명 색상 (Kelvin)</label>
                                    <span id="lightingTempValueDisplay" class="text-xs font-mono text-amber-300 bg-amber-900/30 px-2 py-0.5 rounded border border-amber-500/30">2000K</span>
                                </div>
                                <input type="range" id="lightingTempSlider" min="2000" max="6500" step="100" value="2000" class="w-full h-2 bg-gradient-to-r from-orange-500 via-yellow-100 to-blue-300 rounded-lg appearance-none cursor-pointer" />
                            </div>
                            <div class="pt-2 border-t border-slate-800">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-sm font-medium text-slate-300">반사 강도 (Reflection)</label>
                                    <span id="reflectionValueDisplay" class="text-xs font-mono text-cyan-300 bg-cyan-900/30 px-2 py-0.5 rounded border border-cyan-500/30">50%</span>
                                </div>
                                <input type="range" id="reflectionSlider" min="0" max="100" step="10" value="50" class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer" />
                            </div>
                        </div>

                        <!-- Door -->
                        <!-- Door -->
                        <div class="bg-slate-950/50 p-4 rounded-xl border border-slate-800">
                            <label class="text-sm font-medium text-slate-300 mb-2 block">입구 출입문 (Entrance Door)</label>
                            
                            <div class="border-2 border-dashed border-slate-700 rounded-xl p-4 transition-colors hover:border-blue-500/50 hover:bg-slate-800/50 group relative bg-slate-950/30 h-32 flex flex-col justify-center">
                                <input type="file" id="doorInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                                
                                <div id="doorPlaceholder" class="flex flex-col items-center justify-center pointer-events-none">
                                     <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center mb-2 group-hover:bg-slate-700 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                     </div>
                                     <p class="text-xs font-medium text-slate-300">출입문 텍스처를 여기에 놓으세요</p>
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
                                <label class="text-sm font-medium text-slate-300">바닥 (Floor)</label>
                                <div class="flex bg-slate-800 rounded-lg p-1 border border-slate-700">
                                  <button id="floorModeUpload" class="text-xs px-3 py-1 rounded-md transition-all bg-slate-600 text-white shadow">이미지</button>
                                  <button id="floorModePreset" class="text-xs px-3 py-1 rounded-md transition-all text-slate-400 hover:text-white">선택</button>
                                </div>
                             </div>
                             
                             <div id="floorUploadArea" class="block">
                                <div class="border-2 border-dashed border-slate-700 rounded-xl p-4 transition-colors hover:border-blue-500/50 hover:bg-slate-800/50 group relative bg-slate-950/30 h-32 flex flex-col justify-center">
                                    <input type="file" id="floorInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                                    
                                    <div id="floorPlaceholder" class="flex flex-col items-center justify-center pointer-events-none">
                                         <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center mb-2 group-hover:bg-slate-700 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                         </div>
                                         <p class="text-xs font-medium text-slate-300">바닥재 이미지를 여기에 놓으세요</p>
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
                                <label class="block text-sm font-medium text-slate-300">패널 (1-11)</label>
                                <button type="button" id="applyPanel1Btn" disabled class="text-xs font-medium px-3 py-1.5 rounded-lg transition-all flex items-center gap-2 border text-slate-500 bg-slate-800/50 border-slate-700 cursor-not-allowed">
                                    패널 1을 전체에 복사
                                </button>
                            </div>
                            <div id="panelsContainer" class="max-h-[500px] overflow-y-auto custom-scrollbar pr-2 space-y-2">
                            </div>
                        </div>
                    </div>
                </section>

                <div class="sticky bottom-4 z-10 space-y-3">
                    <button type="button" id="previewPromptBtn" disabled class="w-full py-3 px-6 rounded-xl font-semibold text-slate-300 bg-slate-800/80 border border-slate-700 hover:bg-slate-700 transition-all flex items-center justify-center gap-2 cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        프롬프트 미리보기 (Prompt Preview)
                    </button>
                    <button type="button" id="generateBtn" disabled class="w-full py-4 px-6 rounded-xl font-bold text-lg shadow-lg transition-all duration-300 transform flex items-center justify-center gap-3 bg-slate-800 text-slate-500 cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
                        시각화 생성하기
                    </button>
                    <div id="errorMessage" class="hidden mt-3 p-3 bg-red-500/10 border border-red-500/20 text-red-400 text-sm rounded-lg text-center"></div>
                </div>
            </div>

            <!-- PREVIEW -->
            <div class="w-full lg:w-7/12">
                <div class="bg-slate-900 rounded-2xl border border-slate-800 shadow-xl overflow-hidden h-full min-h-[600px] flex flex-col">
                    <div class="p-4 border-b border-slate-800 flex justify-between items-center bg-slate-900/50 backdrop-blur">
                        <h3 class="font-semibold text-slate-200">결과물 (Pro 2K)</h3>
                        <button id="clearResultBtn" class="hidden text-xs text-slate-400 hover:text-white underline">결과 지우기</button>
                    </div>
                    
                    <!-- Prompt Debug Area -->
                    <div id="promptDebugContainer" class="hidden p-4 bg-slate-950 border-b border-slate-800">
                        <div class="flex items-center justify-between mb-2">
                             <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">최종 프롬프트 (디버그)</label>
                             <button id="copyPromptBtn" class="text-xs bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-2 py-1 rounded transition-colors flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                텍스트만 복사 (이미지 제외)
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
                            <h3 class="text-2xl font-light text-slate-400 mb-2">렌더링 준비 완료</h3>
                            <p class="max-w-xs mx-auto text-slate-600">구조도와 재질을 업로드해주세요.</p>
                        </div>
                        <!-- Loading State -->
                        <div id="loadingOverlay" class="hidden absolute inset-0 bg-slate-950/80 backdrop-blur-sm z-20 flex flex-col items-center justify-center">
                            <div class="w-24 h-24 border-4 border-purple-500/30 border-t-purple-500 rounded-full animate-spin mb-6"></div>
                            <p class="text-purple-300 font-medium animate-pulse">고품질 지오메트리 처리 중...</p>
                            <p id="timerDisplay" class="text-white text-3xl font-mono mt-4 font-bold tracking-wider">00:00</p>
                        </div>
                        <!-- Result -->
                        <div id="resultContainer" class="hidden relative w-full h-full flex items-center justify-center group">
                            <img id="resultImage" src="" class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl shadow-black/50" />
                            <div class="absolute bottom-6 right-6 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a id="downloadLink" href="#" download="elevator_render.png" class="bg-white text-slate-900 px-4 py-2 rounded-lg font-bold shadow-lg hover:bg-slate-200 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    고해상도 다운로드
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Prompt Preview Modal -->
    <div id="promptModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="document.getElementById('promptModal').classList.add('hidden')"></div>
        <div class="relative w-full max-w-5xl h-[85vh] bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl flex flex-col overflow-hidden animate-[fadeIn_0.2s_ease-out]">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-5 border-b border-slate-700 bg-slate-900">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">프롬프트 미리보기 / Prompt Preview</h3>
                        <p class="text-xs text-slate-400">AI에 전송되는 시스템 프롬프트(영문)와 설정 요약(한글)을 확인하세요.</p>
                    </div>
                </div>
                <button id="closePromptModalBtn" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-6 bg-slate-950/50">
                
                <!-- English Section -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-blue-400 uppercase tracking-wider bg-blue-500/10 px-2 py-1 rounded border border-blue-500/20">System Prompt (English)</label>
                        <button id="copyPromptEnBtn" onclick="window.copyToClipboard('promptModalEnContent', 'copyPromptEnBtn')" class="text-xs bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-3 py-1.5 rounded-lg border border-slate-700 transition-all flex items-center gap-2">
                             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                             Copy English
                        </button>
                    </div>
                    <div class="relative group">
                         <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl opacity-20 blur group-hover:opacity-30 transition duration-1000 group-hover:duration-200"></div>
                         <pre id="promptModalEnContent" class="relative w-full h-64 bg-slate-900 border border-slate-700 rounded-xl p-4 text-xs text-slate-400 font-mono overflow-auto custom-scrollbar leading-relaxed whitespace-pre-wrap select-text"></pre>
                    </div>
                </div>

                <!-- Korean Section -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-emerald-400 uppercase tracking-wider bg-emerald-500/10 px-2 py-1 rounded border border-emerald-500/20">Configuration Summary (한국어)</label>
                        <button id="copyPromptKoBtn" onclick="window.copyToClipboard('promptModalKoContent', 'copyPromptKoBtn')" class="text-xs bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-3 py-1.5 rounded-lg border border-slate-700 transition-all flex items-center gap-2">
                             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                             복사 (한국어)
                        </button>
                    </div>
                    <div class="relative group">
                         <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl opacity-20 blur group-hover:opacity-30 transition duration-1000 group-hover:duration-200"></div>
                         <pre id="promptModalKoContent" class="relative w-full h-80 bg-slate-900 border border-slate-700 rounded-xl p-4 text-sm text-slate-300 font-sans overflow-auto custom-scrollbar leading-loose whitespace-pre-wrap select-text"></pre>
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
            layout: { file: null, preview: null, aspectRatio: "1:1", guideFile: null, guidePreview: null },
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

        function updateGuideUI() {
            if (state.layout.guideFile) {
                $('guidePlaceholder').classList.add('hidden');
                $('guidePreviewContainer').classList.remove('hidden');
                $('guidePreviewImage').src = state.layout.guidePreview;
            } else {
                $('guidePlaceholder').classList.remove('hidden');
                $('guidePreviewContainer').classList.add('hidden');
                $('guidePreviewImage').src = "";
            }
        }

        // Guide Events
        $('guideInput').addEventListener('change', (e) => {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                state.layout.guideFile = file;
                state.layout.guidePreview = URL.createObjectURL(file);
                updateGuideUI();
            }
        });

        $('clearGuideBtn').addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            state.layout.guideFile = null;
            state.layout.guidePreview = null;
            $('guideInput').value = '';
            updateGuideUI();
        });

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
                             <span class="text-xs font-medium text-slate-300">모드:</span>
                             <div class="flex bg-slate-700 rounded p-0.5">
                                 <button class="text-[10px] px-2 py-0.5 rounded ${panel.mode === 'upload' ? 'bg-slate-500 text-white' : 'text-slate-400'}" onclick="window.setPanelMode('${panel.id}', 'upload')">이미지</button>
                                 <button class="text-[10px] px-2 py-0.5 rounded ${panel.mode === 'preset' ? 'bg-slate-500 text-white' : 'text-slate-400'}" onclick="window.setPanelMode('${panel.id}', 'preset')">설정</button>
                             </div>
                        </div>`;
                if (panel.mode === 'upload') {
                    if (panel.file) {
                        content += `
                            <div class="relative w-full h-12 bg-slate-900 rounded overflow-hidden group">
                                <img src="${panel.previewUrl}" class="w-full h-full object-cover opacity-80" />
                                <button onclick="window.removePanelFile('${panel.id}')" class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 text-white text-xs">삭제</button>
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
                                <span class="text-[10px] text-slate-400 pointer-events-none">+ 업로드 / 드롭</span>
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
                                <option value="hairline" ${panel.presetType === 'hairline' ? 'selected' : ''}>헤어라인 (결무늬)</option>
                                <option value="mirror" ${panel.presetType === 'mirror' ? 'selected' : ''}>미러 (거울면)</option>
                                <option value="vibration" ${panel.presetType === 'vibration' ? 'selected' : ''}>바이브레이션 (스월)</option>
                                <option value="bead" ${panel.presetType === 'bead' ? 'selected' : ''}>비드 블라스트 (무광)</option>
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
                case 'gold': return 'linear-gradient(135deg, #FFD700 0%, #FDB931 100%)';
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

        // --- NEW: Prompt Preview Logic ---
        
        // Helper to generate reflection description
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

        // Reusable Prompt Builder
        async function buildGeminiPrompt(currentState) {
            const parts = [];
            
            // 1. System/Context Instruction
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
                
                CRITICAL VISUAL STRUCTURE DEFINITIONS:
                The elevator cabin has a specific, fixed layout relative to the Central Doors. You must understand this spatial arrangement:

                [CENTER]: Elevator Doors (Material A)
                
                [RIGHT WALL SIDE]:
                - **Panel #1**: Immediately to the RIGHT of the Door. (Front Wall Right Return)
                - **Panel #2**: Meets Panel #1 at the corner. This is the **NARROWEST** panel.
                - **Panel #3**: Next to Panel #2. This is the **WIDEST** panel on this side.
                - **Panel #4**: Next to Panel #3. Width is approx 2x of Panel #2. Same width as Panel #8.
                
                [LEFT WALL SIDE] (Symmetric to Right):
                - **Panel #11**: Immediately to the LEFT of the Door. (Front Wall Left Return). Symmetric to Panel #1.
                - **Panel #10**: Meets Panel #11 at the corner. **NARROWEST** panel. Same width as Panel #2.
                - **Panel #9**: Next to Panel #10. **WIDEST** panel. Same width as Panel #3.
                - **Panel #8**: Next to Panel #9. Same width as Panel #4.

                [VERTICAL RULE]:
                - All panels extend continuously from the Floor to the Ceiling.
                - The material/texture of a panel MUST be consistent from top to bottom. Do not split a panel vertically.

                CRITICAL: NO WATERMARKS / NO SIGNATURES
                - Do NOT include any "diamond shape" mark, logo, or signature in the bottom right corner.
                - The final image must be clean and free of any AI generation watermarks or artist signatures.
                `
            });

            // 2. The Layout Image
            parts.push({text: "REFERENCE LAYOUT STRUCTURE (GROUND TRUTH):"});
            parts.push(await fileToPart(currentState.layout.file));

            if (currentState.layout.guideFile) {
                parts.push({text: "REFERENCE PANEL NUMBER MAP (Use this to identify panel numbers):"});
                parts.push(await fileToPart(currentState.layout.guideFile));
                parts.push({text: `INSTRUCTION: The image above explicitly labels the panel numbers (1, 2, 3, etc.). Use this map to correctly apply 'Material Bx' to 'Panel x'. The visual position of the number in this map corresponds to the panel with that ID.

CRITICAL VISIBILITY RULE: 
- Any panel number NOT explicitly numbered/labeled in this 'Panel Number Map' must be IGNORED.
- If a panel ID (e.g., #5, #6, #7) is NOT shown in the map, it is considered OUT OF VIEW (invisible).
- Do NOT render or attempt to apply materials to panels that are not explicitly identified in the structure.
- Only render the panels whose numbers are explicitly shown.
`});
            }

            if (currentState.door.file) {
                parts.push({text: "MATERIAL A (Use for Main Entrance Doors):"});
                parts.push(await fileToPart(currentState.door.file));
            }

            if (currentState.floor.mode === 'upload' && currentState.floor.file) {
                parts.push({text: "MATERIAL C (Floor):"});
                parts.push(await fileToPart(currentState.floor.file));
            } else {
                 let floorDesc = `FLOOR MATERIAL: ${currentState.floor.preset}`;
                 if (currentState.floor.preset === 'marble') {
                     floorDesc = "FLOOR MATERIAL: High-gloss luxury MARBLE STONE. White/Grey veining. Reflective polished surface. DISTINCT from the metal doors.";
                 } else if (currentState.floor.preset === 'deco') {
                     floorDesc = "FLOOR MATERIAL: Standard architectural DECO-TILE. Matte/Satin finish. Square tiling pattern. DISTINCT from the metal doors.";
                 }
                 if (currentState.door.file) {
                     floorDesc += " [CRITICAL: Do NOT use 'MATERIAL A (Door)' for the floor. The floor must use the specific material described here.]";
                 }
                 parts.push({text: floorDesc});
            }

            let matIndex = 1;
            for (const p of currentState.panels) {
                let posContext = "";
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
                    const reflectionDesc = getReflectionContext(currentState.reflectionIntensity, p.presetType);
                    parts.push({text: `MATERIAL B${matIndex} (Panel ${p.id}):${posContext} Color ${p.presetColor}. Type ${p.presetType}. ${reflectionDesc}`});
                    matIndex++;
                }
            }

            const reflectionGlobal = getReflectionContext(currentState.reflectionIntensity, 'standard');
            parts.push({text: `GENERATE. Lighting Temperature: ${currentState.lightingTemp}K. Global Reflection Style: ${reflectionGlobal}. Output High-Res 3D Render.`});
            
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

            return parts;
        }

        // Korean Summary Generator
        function generateKoreanSummary(currentState) {
            let summary = "";
            summary += "=== 🏗️ 기본 설정 ===\n";
            summary += `• 구조 레이아웃: ${currentState.layout.file ? currentState.layout.file.name : '없음'}\n`;
            summary += `• 화면비율 (Aspect Ratio): ${currentState.layout.aspectRatio}\n`;
            summary += `• 조명 온도: ${currentState.lightingTemp}K\n`;
            summary += `• 반사 강도: ${currentState.reflectionIntensity}%\n\n`;

            summary += "=== 🚪 출입문 (Entrance) ===\n";
            if (currentState.door.file) {
                summary += `• [이미지] ${currentState.door.file.name}\n`;
            } else {
                summary += `• 설정되지 않음 (기본 재질)\n`;
            }
            summary += "\n";

            summary += "=== 🦶 바닥 (Floor) ===\n";
            if (currentState.floor.mode === 'upload' && currentState.floor.file) {
                summary += `• [이미지] ${currentState.floor.file.name}\n`;
            } else {
                const presetName = currentState.floor.preset === 'marble' ? '대리석 (Marble)' : '데코타일 (Deco Tile)';
                summary += `• [프리셋] ${presetName}\n`;
            }
            summary += "\n";

            summary += "=== 🧱 벽면 패널 (Panels) ===\n";
            const panelGroups = [];
            // Group similar panels for cleaner output
            let currentGroup = null;
            
            currentState.panels.forEach(p => {
                let desc = "";
                if (p.mode === 'upload') {
                     desc = p.file ? `[이미지] ${p.file.name}` : '(이미지 없음)';
                } else {
                     const colorMap = { 'silver': '실버', 'gold': '골드', 'bronze': '브론즈', 'black': '블랙' };
                     const typeMap = { 'hairline': '헤어라인', 'mirror': '미러', 'vibration': '바이브레이션', 'bead': '비드 블라스트' };
                     desc = `[설정] ${colorMap[p.presetColor]} / ${typeMap[p.presetType]}`;
                }
                
                if (!currentGroup) {
                    currentGroup = { ids: [p.id], desc: desc };
                } else {
                    if (currentGroup.desc === desc) {
                        currentGroup.ids.push(p.id);
                    } else {
                        panelGroups.push(currentGroup);
                        currentGroup = { ids: [p.id], desc: desc };
                    }
                }
            });
            if (currentGroup) panelGroups.push(currentGroup);

            panelGroups.forEach(g => {
                const ids = g.ids.length > 1 ? `${g.ids[0]}~${g.ids[g.ids.length-1]}` : g.ids[0];
                const idList = g.ids.join(', ');
                summary += `• 패널 #${idList}: ${g.desc}\n`;
            });

            return summary;
        }

        // Preview Handlers
        $('previewPromptBtn').addEventListener('click', async () => {
             if (!state.layout.file) return;
             
             // Show Modal with Loading
             $('promptModal').classList.remove('hidden');
             $('promptModalEnContent').textContent = "Generating prompt...";
             $('promptModalKoContent').textContent = "한글 요약 생성 중...";

             try {
                 // 1. Generate English Prompt
                 const parts = await buildGeminiPrompt(state);
                 let debugText = "";
                 parts.forEach(p => {
                    if (p.text) {
                        debugText += p.text.trim() + "\n\n";
                    } else if (p.inlineData) {
                        debugText += `[IMAGE DATA: ${p.inlineData.mimeType}]\n\n`;
                    }
                 });
                 $('promptModalEnContent').textContent = debugText;

                 // 2. Generate Korean Summary
                 const koText = generateKoreanSummary(state);
                 $('promptModalKoContent').textContent = koText;

             } catch (e) {
                 $('promptModalEnContent').textContent = "Error generating prompt: " + e.message;
             }
        });

        $('closePromptModalBtn').addEventListener('click', () => {
            $('promptModal').classList.add('hidden');
        });
        
        // Copy Handlers for Modal
        window.copyToClipboard = (elementId, btnId) => {
            const text = $(elementId).textContent;
            navigator.clipboard.writeText(text).then(() => {
                const btn = $(btnId);
                const originalHtml = btn.innerHTML;
                btn.innerHTML = `<span class="text-emerald-400">Copied!</span>`;
                setTimeout(() => btn.innerHTML = originalHtml, 2000);
            });
        };

        // UI Logic to Enable Preview Button
        const originalUpdateLayoutUI = updateLayoutUI;
        updateLayoutUI = function() { // Override to hook into updates
             originalUpdateLayoutUI();
             const btn = $('previewPromptBtn');
             if (state.layout.file) {
                 btn.disabled = false;
                 btn.classList.remove('text-slate-300', 'bg-slate-800/80', 'cursor-not-allowed');
                 btn.classList.add('text-white', 'bg-slate-700/80', 'hover:bg-slate-600', 'cursor-pointer', 'border-indigo-500/50', 'ring-1', 'ring-indigo-500/30');
             } else {
                 btn.disabled = true;
                 btn.classList.add('text-slate-300', 'bg-slate-800/80', 'cursor-not-allowed');
                 btn.classList.remove('text-white', 'bg-slate-700/80', 'hover:bg-slate-600', 'cursor-pointer', 'border-indigo-500/50', 'ring-1', 'ring-indigo-500/30');
             }
        };

        // Generation
        $('generateBtn').addEventListener('click', async () => {
            if (!state.layout.file) return;
            state.isGenerating = true;
            
            // UI Updates for Generation Start
            const btn = $('generateBtn');
            const originalBtnContent = btn.innerHTML;
            btn.disabled = true;
            btn.classList.add('cursor-not-allowed', 'opacity-75');
            
            // Reset UI for fresh generation
            $('loadingOverlay').classList.remove('hidden');
            $('resultContainer').classList.add('hidden'); // HIDDEN during generation
            $('resultImage').src = ""; // Clear old image
            $('errorMessage').classList.add('hidden');
            $('promptDebugContainer').classList.add('hidden');
            $('promptDebugText').value = ""; // Clear old debug text
            
            let seconds = 0;
            // Update button text immediately
            btn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> 생성 중... 00:00`;
            
            state.timerId = setInterval(() => { 
                seconds++; 
                const timeStr = formatTime(seconds);
                $('timerDisplay').textContent = timeStr;
                btn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> 생성 중... ${timeStr}`;
            }, 1000);

            try {
                // CRITICAL: gemini-3-pro-image-preview requires PAID tier
                // This model is specifically for high-quality image generation
                const genAI = new GoogleGenerativeAI(state.apiKey);
                const model = genAI.getGenerativeModel({ model: "gemini-3-pro-image-preview" });
                // ⚠️ CRITICAL: STRUCTURE MUST NOT BE MODIFIED ⚠️
                // (Prompt structure logic moved to buildGeminiPrompt)
                const parts = await buildGeminiPrompt(state);

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
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg> 시각화 생성하기`;
            }
        });

        // Initialize Default Guide
        async function initDefaultGuide() {
            try {
                // Check if user already uploaded something (unlikely on reload unless state persisted, but good practice)
                if (state.layout.guideFile) return;

                const response = await fetch('sourceimg/car_inner.jpg');
                if (!response.ok) throw new Error('Default guide image not found');
                
                const blob = await response.blob();
                const file = new File([blob], "car_inner.jpg", { type: blob.type });
                
                state.layout.guideFile = file;
                state.layout.guidePreview = URL.createObjectURL(file);
                updateGuideUI();
                console.log("Default guide loaded: car_inner.jpg");
            } catch (e) {
                console.warn("Could not load default guide:", e);
            }
        }

        // Initialize Default Layout (Wireframe)
        async function initDefaultLayout() {
             try {
                if (state.layout.file) return;

                const response = await fetch('sourceimg/car_basic_front.jpg');
                if (!response.ok) throw new Error('Default layout wireframe not found');
                
                const blob = await response.blob();
                const file = new File([blob], "car_basic_front.jpg", { type: blob.type });
                
                state.layout.file = file;
                state.layout.preview = URL.createObjectURL(file);
                // Assume default aspect ratio or let updateLayoutUI handle it (defaults to 1:1 in state, maybe update if needed)
                state.layout.aspectRatio = "3:4"; // Assuming portrait for this specific image based on filename "car_basic_front" common verticality
                updateLayoutUI();
                console.log("Default layout loaded: car_basic_front.jpg");
            } catch (e) {
                console.warn("Could not load default layout:", e);
            }
        }

        renderPanels();
        updateLayoutUI();
        updateFloorUI();
        updateDoorUI();
        
        // Load Defaults
        (async () => {
            await Promise.all([initDefaultGuide(), initDefaultLayout()]);
        })();
    </script>
    <?php endif; ?>
</body>
</html>
