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
                
                    <!-- View Toggle -->
                    <div class="bg-slate-900 p-2 rounded-2xl border border-slate-800 shadow-lg flex gap-2">
                        <button id="viewModeFront" class="flex-1 py-3 text-sm font-bold rounded-xl transition-all bg-blue-600 text-white shadow-lg flex items-center justify-center gap-2">
                            <span>전면 뷰 (Front)</span>
                        </button>
                        <button id="viewModeRear" class="flex-1 py-3 text-sm font-bold rounded-xl transition-all text-slate-400 hover:text-white hover:bg-slate-800 flex items-center justify-center gap-2">
                            <span>후면 뷰 (Rear)</span>
                        </button>
                    </div>

                    <!-- Layout -->
                    <section class="bg-slate-900 rounded-2xl p-6 border border-slate-800 shadow-xl">
                        <div class="flex items-center justify-between mb-4">
                            <h2 id="layoutTitle" class="text-lg font-semibold text-white flex items-center gap-2">
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
                            <!-- Reflection UI -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-slate-300">반사 강도 (Reflection)</label>
                                <div class="flex bg-slate-800 rounded-lg p-1 border border-slate-700">
                                  <button id="refModeGlobal" class="text-xs px-3 py-1 rounded-md transition-all bg-slate-600 text-white shadow">전체</button>
                                  <button id="refModeIndividual" class="text-xs px-3 py-1 rounded-md transition-all text-slate-400 hover:text-white">개별</button>
                                </div>
                            </div>
                            
                            <!-- Global Mode -->
                            <div id="reflectionGlobalArea" class="block bg-slate-900/50 p-3 rounded-lg border border-slate-700">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs text-slate-400">전체 반사 (Global Intensity)</span>
                                    <span id="reflectionValueDisplay" class="text-xs font-mono text-cyan-400">50%</span>
                                </div>
                                <input type="range" id="reflectionSlider" min="0" max="100" value="50" class="w-full h-1.5 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-cyan-500">
                                <div class="flex justify-between text-[10px] text-slate-500 mt-1">
                                    <span>Matte</span>
                                    <span>Standard</span>
                                    <span>Mirror</span>
                                </div>
                            </div>

                            <!-- Individual Mode -->
                            <div id="reflectionIndividualArea" class="hidden space-y-3">
                                <!-- Door & Floor Wrapper -->
                                <div class="grid grid-cols-1 gap-3">
                                    <div class="bg-slate-900/50 p-2 rounded-lg border border-slate-700">
                                         <div class="flex justify-between items-center mb-1">
                                            <span class="text-xs text-slate-300">🚪 출입문 (Door)</span>
                                            <span id="refDoorValue" class="text-xs font-mono text-cyan-400">50%</span>
                                        </div>
                                        <input type="range" id="refDoorSlider" min="0" max="100" value="50" class="w-full h-1 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-cyan-500">
                                    </div>
                                    <div class="bg-slate-900/50 p-2 rounded-lg border border-slate-700">
                                         <div class="flex justify-between items-center mb-1">
                                            <span class="text-xs text-slate-300">🦶 바닥 (Floor)</span>
                                            <span id="refFloorValue" class="text-xs font-mono text-cyan-400">50%</span>
                                        </div>
                                        <input type="range" id="refFloorSlider" min="0" max="100" value="50" class="w-full h-1 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-cyan-500">
                                    </div>
                                </div>

                                <!-- Panels List -->
                                <div class="bg-slate-900/50 p-3 rounded-lg border border-slate-700">
                                    <div class="mb-2 text-xs font-semibold text-slate-400">🧱 패널 반사 (Panel Reflections)</div>
                                    <div class="max-h-[200px] overflow-y-auto custom-scrollbar pr-2 space-y-2">
                                        <?php for($i=1; $i<=11; $i++): ?>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] text-slate-500 w-8">P#<?php echo $i; ?></span>
                                            <input type="range" id="refPanel<?php echo $i; ?>Slider" min="0" max="100" value="50" class="flex-1 h-1 bg-slate-800 rounded appearance-none cursor-pointer accent-cyan-500" oninput="document.getElementById('refPanel<?php echo $i; ?>Value').textContent = this.value + '%'">
                                            <span id="refPanel<?php echo $i; ?>Value" class="text-[10px] font-mono text-cyan-400 w-8 text-right">50%</span>
                                        </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>

                        <!-- Door -->
                        <!-- Door -->
                        <div id="doorSection" class="bg-slate-950/50 p-4 rounded-xl border border-slate-800">
                            <div class="flex items-center justify-between mb-3">
                                <label class="text-sm font-medium text-slate-300 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-500"><path d="M14 2v20H2V2h12z"/><path d="M14 2v20h8V2h-8z"/><path d="M4 12h2"/><path d="M16 12h2"/></svg>
                                    입구 출입문 (Entrance Door)
                                </label>
                                <div class="flex bg-slate-800 rounded-lg p-1 border border-slate-700">
                                  <button id="doorModeUpload" class="text-xs px-3 py-1 rounded-md transition-all bg-slate-600 text-white shadow">이미지</button>
                                  <button id="doorModePreset" class="text-xs px-3 py-1 rounded-md transition-all text-slate-400 hover:text-white">선택</button>
                                </div>
                            </div>
                            
                            <!-- Upload Area -->
                            <div id="doorUploadArea" class="block">
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

                            <!-- Preset Area -->
                            <div id="doorPresetArea" class="hidden flex flex-col gap-3">
                                 <!-- Color Circles -->
                                 <div class="flex gap-3 justify-center bg-slate-900 p-3 rounded-lg border border-slate-700">
                                    <?php 
                                    $colors = ['silver', 'gold', 'bronze', 'black'];
                                    $gradients = [
                                        'silver' => 'linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%)',
                                        'gold' => 'linear-gradient(135deg, #FFD700 0%, #FDB931 100%)',
                                        'bronze' => 'linear-gradient(135deg, #d4a373 0%, #8a5a44 100%)',
                                        'black' => 'linear-gradient(135deg, #434343 0%, #000000 100%)'
                                    ];
                                    foreach ($colors as $color) {
                                        $gradient = $gradients[$color];
                                        echo "<button type='button' class='door-color-btn w-8 h-8 rounded-full relative shadow hover:scale-110 transition-transform opacity-70 hover:opacity-100' data-color='$color' style='background: $gradient;' title='$color'></button>";
                                    }
                                    ?>
                                 </div>
                                 
                                 <!-- Texture Select -->
                                 <select id="doorPresetType" class="w-full bg-slate-900 text-slate-300 text-xs p-2.5 border border-slate-700 rounded-lg text-center appearance-none cursor-pointer hover:border-slate-500 focus:border-purple-500 focus:outline-none">
                                    <option value="hairline">헤어라인 (결무늬)</option>
                                    <option value="mirror">미러 (거울면)</option>
                                    <option value="vibration">바이브레이션 (스월)</option>
                                    <option value="bead">비드 블라스트 (무광)</option>
                                </select>
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
            viewMode: 'front', // 'front' or 'rear'
            layout: { file: null, preview: null, aspectRatio: "1:1", guideFile: null, guidePreview: null },
            door: { file: null, preview: null, mode: 'upload', presetColor: 'silver', presetType: 'hairline' },
            floor: { mode: 'upload', file: null, preview: null, preset: 'deco-tile' },
            panels: Array.from({ length: 11 }, (_, i) => ({
                id: (i + 1).toString(), mode: 'upload', file: null, previewUrl: null, presetType: 'hairline', presetColor: 'silver'
            })),
            lightingTemp: 4500,
            reflection: {
                mode: 'global', // 'global' or 'individual'
                global: 50,
                door: 50,
                floor: 50,
                panels: Array(11).fill(50) 
            },
            isGenerating: false,
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
            // Mode Toggles
            if (state.door.mode === 'upload') {
                $('doorModeUpload').classList.add('bg-slate-600', 'text-white', 'shadow');
                $('doorModeUpload').classList.remove('text-slate-400', 'hover:text-white');
                $('doorModePreset').classList.remove('bg-slate-600', 'text-white', 'shadow');
                $('doorModePreset').classList.add('text-slate-400', 'hover:text-white');
                
                $('doorUploadArea').classList.remove('hidden');
                $('doorPresetArea').classList.add('hidden');

                if (state.door.file) {
                     $('doorPlaceholder').classList.add('hidden');
                     $('doorPreviewContainer').classList.remove('hidden');
                     $('doorPreviewImage').src = state.door.preview;
                } else {
                     $('doorPlaceholder').classList.remove('hidden');
                     $('doorPreviewContainer').classList.add('hidden');
                     $('doorPreviewImage').src = "";
                }
            } else {
                $('doorModePreset').classList.add('bg-slate-600', 'text-white', 'shadow');
                $('doorModePreset').classList.remove('text-slate-400', 'hover:text-white');
                $('doorModeUpload').classList.remove('bg-slate-600', 'text-white', 'shadow');
                $('doorModeUpload').classList.add('text-slate-400', 'hover:text-white');
                
                $('doorUploadArea').classList.add('hidden');
                $('doorPresetArea').classList.remove('hidden');

                // Update Preset UI
                $('doorPresetType').value = state.door.presetType;
                document.querySelectorAll('.door-color-btn').forEach(btn => {
                    const color = btn.dataset.color;
                    if (color === state.door.presetColor) {
                        btn.classList.add('ring-2', 'ring-white', 'ring-offset-2', 'ring-offset-slate-900', 'scale-110', 'opacity-100');
                        btn.classList.remove('opacity-70');
                        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 drop-shadow-md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>`;
                    } else {
                        btn.classList.remove('ring-2', 'ring-white', 'ring-offset-2', 'ring-offset-slate-900', 'scale-110', 'opacity-100');
                         btn.classList.add('opacity-70');
                        btn.innerHTML = '';
                    }
                });
            }
        }
        
        $('doorInput').addEventListener('change', (e) => {
            if (e.target.files[0]) { 
                state.door.file = e.target.files[0]; 
                state.door.preview = URL.createObjectURL(state.door.file);
                // Ensure we stay in upload mode if file is selected directly (though UI hides input in preset mode)
                state.door.mode = 'upload';
                updateDoorUI(); 
            }
        });
        $('clearDoorBtn').addEventListener('click', () => { 
            state.door = { ...state.door, file: null, preview: null }; 
            $('doorInput').value = ''; 
            updateDoorUI(); 
        });

        // Door Mode & Preset Events
        $('doorModeUpload').addEventListener('click', () => { state.door.mode = 'upload'; updateDoorUI(); });
        $('doorModePreset').addEventListener('click', () => { state.door.mode = 'preset'; updateDoorUI(); });
        $('doorPresetType').addEventListener('change', (e) => { state.door.presetType = e.target.value; updateDoorUI(); });
        document.querySelectorAll('.door-color-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                state.door.presetColor = e.target.dataset.color;
                updateDoorUI();
            });
        });

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
        // View Mode Logic
        async function loadViewDefaults() {
            // Clear current Layout/Guide
            state.layout.file = null;
            state.layout.preview = null;
            state.layout.guideFile = null;
            state.layout.guidePreview = null;
            $('layoutInput').value = '';
            $('guideInput').value = '';
            
            if (state.viewMode === 'front') {
                await initDefaultLayout('sourceimg/car_basic_front.jpg');
                await initDefaultGuide('sourceimg/car_inner.jpg');
            } else {
                await initDefaultLayout('sourceimg/car_basic_rear.jpg');
                await initDefaultGuide('sourceimg/car_inner_rear.jpg');
            }
            updateLayoutUI();
            updateGuideUI();
        }

        function updateViewModeUI() {
            if (state.viewMode === 'front') {
                $('viewModeFront').classList.add('bg-blue-600', 'text-white', 'shadow-lg');
                $('viewModeFront').classList.remove('text-slate-400', 'hover:text-white');
                $('viewModeRear').classList.remove('bg-blue-600', 'text-white', 'shadow-lg');
                $('viewModeRear').classList.add('text-slate-400', 'hover:text-white');
                
                $('doorSection').classList.remove('hidden');
                document.getElementById('layoutTitle').innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                    Structural Layout (Front)
                `;
            } else {
                $('viewModeRear').classList.add('bg-blue-600', 'text-white', 'shadow-lg');
                $('viewModeRear').classList.remove('text-slate-400', 'hover:text-white');
                $('viewModeFront').classList.remove('bg-blue-600', 'text-white', 'shadow-lg');
                $('viewModeFront').classList.add('text-slate-400', 'hover:text-white');
                
                $('doorSection').classList.add('hidden');
                document.getElementById('layoutTitle').innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                    Structural Layout (Rear)
                `;
            }
        }

        $('viewModeFront').addEventListener('click', async () => { 
            if(state.viewMode === 'front') return;
            state.viewMode = 'front'; 
            updateViewModeUI(); 
            await loadViewDefaults();
        });
        $('viewModeRear').addEventListener('click', async () => { 
            if(state.viewMode === 'rear') return;
            state.viewMode = 'rear'; 
            updateViewModeUI(); 
            await loadViewDefaults();
        });

        $('layoutInput').addEventListener('change', (e) => {
            if (e.target.files[0]) {
                state.layout.file = e.target.files[0];
                state.layout.preview = URL.createObjectURL(state.layout.file);
                
                // Estimate Aspect Ratio (simple)
                const img = new Image();
                img.onload = () => {
                    const ratio = img.width / img.height;
                    state.layout.aspectRatio = ratio > 1 ? "4:3" : "3:4"; 
                    updateLayoutUI();
                };
                img.src = URL.createObjectURL(state.layout.file);
            }
        });
        $('clearLayoutBtn').addEventListener('click', () => { 
            state.layout = { file: null, preview: null, aspectRatio: "1:1", guideFile: state.layout.guideFile, guidePreview: state.layout.guidePreview }; 
            $('layoutInput').value = ''; 
            updateLayoutUI(); 
        });

        $('lightingTempSlider').addEventListener('input', (e) => {
            state.lightingTemp = e.target.value;
            $('lightingTempValueDisplay').textContent = state.lightingTemp + "K";
        });

        // Reflection UI Logic
        function updateReflectionUI() {
            if (state.reflection.mode === 'global') {
                $('refModeGlobal').classList.add('bg-slate-600', 'text-white', 'shadow');
                $('refModeGlobal').classList.remove('text-slate-400', 'hover:text-white');
                $('refModeIndividual').classList.remove('bg-slate-600', 'text-white', 'shadow');
                $('refModeIndividual').classList.add('text-slate-400', 'hover:text-white');
                
                $('reflectionGlobalArea').classList.remove('hidden');
                $('reflectionIndividualArea').classList.add('hidden');
                
                // Sync display
                $('reflectionValueDisplay').textContent = state.reflection.global + "%";
                $('reflectionSlider').value = state.reflection.global;
            } else {
                $('refModeIndividual').classList.add('bg-slate-600', 'text-white', 'shadow');
                $('refModeIndividual').classList.remove('text-slate-400', 'hover:text-white');
                $('refModeGlobal').classList.remove('bg-slate-600', 'text-white', 'shadow');
                $('refModeGlobal').classList.add('text-slate-400', 'hover:text-white');
                
                $('reflectionGlobalArea').classList.add('hidden');
                $('reflectionIndividualArea').classList.remove('hidden');
                
                // Sync individual inputs
                $('refDoorSlider').value = state.reflection.door;
                $('refDoorValue').textContent = state.reflection.door + "%";
                
                $('refFloorSlider').value = state.reflection.floor;
                $('refFloorValue').textContent = state.reflection.floor + "%";
                
                state.reflection.panels.forEach((val, i) => {
                    const slider = document.getElementById(`refPanel${i+1}Slider`);
                    if(slider) {
                        slider.value = val;
                        document.getElementById(`refPanel${i+1}Value`).textContent = val + "%";
                    }
                });
            }
        }

        // Reflection Event Listeners
        $('refModeGlobal').addEventListener('click', () => { state.reflection.mode = 'global'; updateReflectionUI(); });
        $('refModeIndividual').addEventListener('click', () => { state.reflection.mode = 'individual'; updateReflectionUI(); });
        
        $('reflectionSlider').addEventListener('input', (e) => {
            state.reflection.global = parseInt(e.target.value);
            $('reflectionValueDisplay').textContent = state.reflection.global + "%";
        });
        
        $('refDoorSlider').addEventListener('input', (e) => {
            state.reflection.door = parseInt(e.target.value);
            $('refDoorValue').textContent = state.reflection.door + "%";
        });
        
        $('refFloorSlider').addEventListener('input', (e) => {
            state.reflection.floor = parseInt(e.target.value);
            $('refFloorValue').textContent = state.reflection.floor + "%";
        });
        
        // Panel Reflection Sliders
        for (let i = 1; i <= 11; i++) {
            const slider = document.getElementById(`refPanel${i}Slider`);
            if (slider) {
                slider.addEventListener('input', (e) => {
                    state.reflection.panels[i-1] = parseInt(e.target.value);
                    // Value display is updated inline in HTML oninput, but let's ensure consistency
                    document.getElementById(`refPanel${i}Value`).textContent = state.reflection.panels[i-1] + "%";
                });
            }
        }

        updateReflectionUI(); // Init

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
                
                CRITICAL: VERTICAL PANELS VS HORIZONTAL HANDRAIL
                - The layout includes a **HORIZONTAL HANDRAIL** (Bar) running across the panels (typically on the Left/Right/Rear walls).
                - **DO NOT** cover this handrail with the panel material.
                - The 'Material Bx' you apply to a panel must go **BEHIND** the handrail.
                - The Handrail itself must remain visible as a distinct 3D object (typically Stainless Steel or Chrome).
                - **PROTECT THE HANDRAIL GEOMETRY.** Do not flatten it into the wall.
                
                CRITICAL VISUAL STRUCTURE DEFINITIONS:
                The elevator cabin has a specific, fixed layout relative to the Central axis. You must understand this spatial arrangement:

                ${currentState.viewMode === 'front' ? 
                `[CENTER]: Elevator Doors (Material A)
                
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
                ` : 
                `[CENTER]: **Panel #6** (REAR WALL CENTER). This is the main central panel of the rear wall.
                
                [RIGHT SIDE of Image] (Physical Left Wall):
                - **Panel #7**: Immediately to the Right of Center Panel #6.
                - **Panel #8**: Corner/Side Panel. (Corresponds to Front View Panel #2). Narrow.
                - **Panel #9**: Side Panel. (Corresponds to Front View Panel #3). Wide.
                - **Panel #10**: Side Panel. (Corresponds to Front View Panel #4).
                
                [LEFT SIDE of Image] (Physical Right Wall):
                - **Panel #5**: Immediately to the Left of Center Panel #6.
                - **Panel #4**: Corner/Side Panel. (Corresponds to Front View Panel #10). Narrow.
                - **Panel #3**: Side Panel. (Corresponds to Front View Panel #9). Wide.
                - **Panel #2**: Side Panel. (Corresponds to Front View Panel #8).
                
                NOTE: Panels #1 and #11 are generally NOT VISIBLE in this Rear View perspective, or are effectively replaced by the corner returns.`
                }

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

            // Helper for reflection intensity
            const getRefInt = (key, idx = -1) => {
                if (currentState.reflection.mode === 'global') return currentState.reflection.global;
                if (key === 'door') return currentState.reflection.door;
                if (key === 'floor') return currentState.reflection.floor;
                if (key === 'panel' && idx >= 0) return currentState.reflection.panels[idx];
                return 50;
            };

            if (currentState.viewMode === 'front') {
                if (currentState.door.mode === 'upload' && currentState.door.file) {
                    parts.push({text: "MATERIAL A (Elevator Doors - Left & Right):"});
                    parts.push(await fileToPart(currentState.door.file));
                    const ref = getReflectionContext(getRefInt('door'), 'standard');
                    parts.push({text: `INSTRUCTION: Apply the material/pattern shown in 'MATERIAL A' to the Center Elevator Doors. ${ref}`});
                } else if (currentState.door.mode === 'preset') {
                    const color = currentState.door.presetColor;
                    const type = currentState.door.presetType;
                    const ref = getReflectionContext(getRefInt('door'), type);
                    let desc = `Color: ${color}, Finish: ${type}. ${ref}`;
                    if (type === 'mirror') desc += " (Highly Reflective Mirror Finish)";
                    
                    parts.push({text: `MATERIAL A (Elevator Doors - Left & Right): ${desc}`});
                    parts.push({text: "INSTRUCTION: Apply this specific metal finish and color to the Center Elevator Doors."});
                }
            }

            if (currentState.floor.mode === 'upload' && currentState.floor.file) {
                 parts.push({text: "MATERIAL FLOOR:"});
                 parts.push(await fileToPart(currentState.floor.file));
                 const ref = getReflectionContext(getRefInt('floor'), 'standard');
                 parts.push({text: `INSTRUCTION: Apply this material to the floor. ${ref}`});
                 parts.push({text: "STRICT REQUIREMENT: NO VARIATION. You MUST apply this exact texture image to the floor. Do NOT change the pattern size, orientation, or style. The floor MUST look exactly like the provided 'MATERIAL FLOOR' image."});
            } else if (currentState.floor.mode === 'preset') {
                 const ref = getReflectionContext(getRefInt('floor'), 'standard');
                 parts.push({text: `MATERIAL FLOOR: ${currentState.floor.preset}. ${ref}`});
                 parts.push({text: "INSTRUCTION: Apply this specific tiling pattern to the floor."});
            }

            // Panels
            for (const [i, p] of currentState.panels.entries()) {
                let posContext = "";
                if (p.id === '1') posContext = " [IMPORTANT: Right of Doors] ";
                else if (p.id === '11') posContext = " [IMPORTANT: Left of Doors] ";
                
                const refInt = getRefInt('panel', i);

                if (p.mode === 'upload' && p.file) {
                    const ref = getReflectionContext(refInt, 'standard');
                    parts.push({text: `MATERIAL B${p.id} (Panel ${p.id}):${posContext}`});
                    parts.push(await fileToPart(p.file));
                    parts.push({text: `INSTRUCTION: Apply above material to Panel ${p.id}. ${ref}`});
                } else if (p.mode === 'preset') {
                    const color = p.presetColor;
                    const type = p.presetType;
                    const ref = getReflectionContext(refInt, type);
                    
                    let desc = `Color: ${color}, Finish: ${type}. ${ref}`;
                    if (type === 'mirror') desc += " (Highly Reflective Mirror Finish)";
                    
                    parts.push({text: `MATERIAL B${p.id} (Panel ${p.id}):${posContext} ${desc}`});
                    parts.push({text: "INSTRUCTION: Apply this metal finish to Panel " + p.id});
                }
            }

            // Lighting & Global Context
            const globalRef = (currentState.reflection.mode === 'global') 
                ? getReflectionContext(currentState.reflection.global, 'standard') 
                : "Reflection intensities are defined individually for each surface.";
                
            parts.push({text: `LIGHTING: Temperature ${currentState.lightingTemp}K. Global Context: ${globalRef}`});
            
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
            if (currentState.layout.guideFile) summary += `• 패널 번호 안내도: 포함됨 (${currentState.layout.guideFile.name})\n`;
            summary += `• 조명 온도: ${currentState.lightingTemp}K\n`;
            
            if (currentState.reflection.mode === 'global') {
                 summary += `• 반사 강도 (전체): ${currentState.reflection.global}%\n`;
            } else {
                 summary += `• 반사 강도 (개별 설정):\n`;
                 summary += `  - 도어: ${currentState.reflection.door}%\n`;
                 summary += `  - 바닥: ${currentState.reflection.floor}%\n`;
                 summary += `  - 패널: 개별 설정됨 (${currentState.reflection.panels.join('%, ')}%)\n`;
            }
            summary += "\n";

            summary += "=== 🚪 도어 (Door) ===\n";
            if (currentState.door.mode === 'upload' && currentState.door.file) {
                summary += `• 파일: ${currentState.door.file.name}\n`;
            } else if (currentState.door.mode === 'preset') {
                const typeMap = { 'hairline': '헤어라인', 'mirror': '미러', 'vibration': '바이브레이션', 'bead': '비드 블라스트' };
                const colorMap = { 'silver': '실버', 'gold': '골드', 'bronze': '브론즈', 'black': '블랙' };
                summary += `• 설정: ${colorMap[currentState.door.presetColor] || currentState.door.presetColor} + ${typeMap[currentState.door.presetType] || currentState.door.presetType}\n`;
            } else {
                summary += "• 설정되지 않음\n";
            }
            summary += "\n";

            summary += "=== � 바닥 (Floor) ===\n";
            if (currentState.floor.mode === 'upload' && currentState.floor.file) {
                summary += `• 파일: ${currentState.floor.file.name}\n`;
            } else {
                summary += `• 프리셋: ${currentState.floor.preset}\n`;
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
        async function initDefaultGuide(path = 'sourceimg/car_inner.jpg') {
            try {
                // If path is passed, force load even if state exists? Or just logic check
                // For switching views, we cleared state.layout.guideFile so it will load.
                if (state.layout.guideFile && !path) return;

                const response = await fetch(path);
                if (!response.ok) throw new Error('Default guide image not found: ' + path);
                
                const blob = await response.blob();
                const filename = path.split('/').pop();
                const file = new File([blob], filename, { type: blob.type });
                
                state.layout.guideFile = file;
                state.layout.guidePreview = URL.createObjectURL(file);
                updateGuideUI();
                console.log("Default guide loaded:", filename);
            } catch (e) {
                console.warn("Could not load default guide:", e);
            }
        }

        // Initialize Default Layout (Wireframe)
        async function initDefaultLayout(path = 'sourceimg/car_basic_front.jpg') {
             try {
                if (state.layout.file && !path) return;

                const response = await fetch(path);
                if (!response.ok) throw new Error('Default layout wireframe not found: ' + path);
                
                const blob = await response.blob();
                const filename = path.split('/').pop();
                const file = new File([blob], filename, { type: blob.type });
                
                state.layout.file = file;
                state.layout.preview = URL.createObjectURL(file);
                state.layout.aspectRatio = "3:4"; 
                updateLayoutUI();
                console.log("Default layout loaded:", filename);
            } catch (e) {
                console.warn("Could not load default layout:", e);
            }
        }

        // Initialize Default Floor
        async function initDefaultFloor() {
             try {
                if (state.floor.file) return;

                const response = await fetch('sourceimg/car_bottom.jpg');
                if (!response.ok) throw new Error('Default floor image not found');
                
                const blob = await response.blob();
                const file = new File([blob], "car_bottom.jpg", { type: blob.type });
                
                state.floor.file = file;
                state.floor.preview = URL.createObjectURL(file);
                // Ensure correct mode
                state.floor.mode = 'upload';
                updateFloorUI();
                console.log("Default floor loaded: car_bottom.jpg");
            } catch (e) {
                console.warn("Could not load default floor:", e);
            }
        }

        // Initialize Default Door (Entrance)
        async function initDefaultDoor() {
            try {
                if (state.door.file) return;

                const response = await fetch('sourceimg/car_door.jpg');
                if (!response.ok) throw new Error('Default door image not found');
                
                const blob = await response.blob();
                const file = new File([blob], "car_door.jpg", { type: blob.type });
                
                state.door.file = file;
                state.door.preview = URL.createObjectURL(file);
                state.door.mode = 'upload'; // Default to upload mode
                updateDoorUI();
                console.log("Default door loaded: car_door.jpg");
            } catch (e) {
                console.warn("Could not load default door:", e);
            }
        }

        renderPanels();
        updateLayoutUI();
        updateFloorUI();
        updateDoorUI();
        
        // Load Defaults
        (async () => {
            await Promise.all([initDefaultGuide(), initDefaultLayout(), initDefaultFloor(), initDefaultDoor()]);
        })();
    </script>
    <?php endif; ?>
</body>
</html>
