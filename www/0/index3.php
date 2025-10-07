<!DOCTYPE html>
<!-- naver 검색창 같은 것 -->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Bar Prototype</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">

  <!-- Search Bar Section -->
  <div class="max-w-screen-md mx-auto bg-white shadow-md rounded-lg p-4">
    <div class="flex items-center border-2 border-green-500 rounded-full p-2 relative">
      <!-- Search Icon -->
      <div class="px-2 text-green-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8 16l-4-4m0 0l4-4m-4 4h16" />
        </svg>
      </div>
      <!-- Search Input -->
      <input 
        type="text" 
        id="searchInput" 
        class="w-full outline-none text-gray-700" 
        placeholder="검색어를 입력해 주세요." 
        oninput="showSuggestions()" 
      />
      <!-- Mic Icon -->
      <div class="px-2 text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v8m0 0V4m0 4H4m8 0h8" />
        </svg>
      </div>
      <!-- Suggestions Dropdown -->
      <div 
        id="suggestions" 
        class="absolute top-12 left-0 bg-white w-full border border-gray-300 rounded-lg shadow-md hidden z-10"
      >
        <ul class="text-gray-700">
          <!-- Suggestions will appear here -->
        </ul>
      </div>
    </div>

    <!-- Navigation Icons -->
    <div class="mt-4 flex justify-around">
      <!-- Icon Set -->
      <div class="text-center group relative">
        <div class="bg-green-500 w-10 h-10 rounded-full mx-auto flex items-center justify-center text-white transform transition-transform hover:scale-110">
          <span>N</span>
        </div>
        <p class="text-sm text-gray-700 mt-2">메일</p>
        <!-- Tooltip -->
        <div class="absolute bottom-12 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded-lg py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity">
          Mail
        </div>
      </div>
      <div class="text-center group relative">
        <div class="bg-green-300 w-10 h-10 rounded-full mx-auto flex items-center justify-center text-white transform transition-transform hover:scale-110">
          <span>카</span>
        </div>
        <p class="text-sm text-gray-700 mt-2">카페</p>
        <div class="absolute bottom-12 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded-lg py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity">
          Cafe
        </div>
      </div>
      <div class="text-center group relative">
        <div class="bg-green-200 w-10 h-10 rounded-full mx-auto flex items-center justify-center text-white transform transition-transform hover:scale-110">
          <span>블</span>
        </div>
        <p class="text-sm text-gray-700 mt-2">블로그</p>
        <div class="absolute bottom-12 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded-lg py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity">
          Blog
        </div>
      </div>
      <!-- Additional icons with similar tooltip and hover effects -->
    </div>
  </div>

  <script>
    // Autofocus search bar on load
    window.onload = () => {
      document.getElementById('searchInput').focus();
    };

    // Example suggestions
    const suggestionsList = ['검색어 예시 1', '검색어 예시 2', '검색어 예시 3'];

    // Show suggestions based on input
    function showSuggestions() {
      const input = document.getElementById('searchInput').value;
      const suggestions = document.getElementById('suggestions');
      const ul = suggestions.querySelector('ul');

      if (input.length > 0) {
        // Filter suggestions
        const filtered = suggestionsList.filter(suggestion => suggestion.includes(input));
        ul.innerHTML = filtered.map(item => `<li class="p-2 hover:bg-gray-100 cursor-pointer">${item}</li>`).join('');
        suggestions.classList.remove('hidden');
      } else {
        suggestions.classList.add('hidden');
      }
    }
  </script>

</body>
</html>
