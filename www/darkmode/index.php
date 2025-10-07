<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dark Mode Example</title>
  <style>
    :root {
      --background-color: #ffffff;
      --text-color: #000000;
    }
    
    [data-theme="dark"] {
      --background-color: #333333;
      --text-color: #ffffff;
    }
    
    body {
      background-color: var(--background-color);
      color: var(--text-color);
    }
  </style>
</head>
<body>
  <h1>Dark Mode Example</h1>
  <label class="toggle-switch">
    <input type="checkbox">
    <span class="toggle-slider"></span>
  </label>
  
  <script>
    // 위에서 작성한 JavaScript 코드
  </script>
</body>
</html>


<script>
const toggleSwitch = document.querySelector('.toggle-switch input[type="checkbox"]');
const currentTheme = localStorage.getItem('theme');

if (currentTheme) {
  document.documentElement.setAttribute('data-theme', currentTheme);

  if (currentTheme === 'dark') {
    toggleSwitch.checked = true;
  }
}

function switchTheme(e) {
  if (e.target.checked) {
    document.documentElement.setAttribute('data-theme', 'dark');
    localStorage.setItem('theme', 'dark');
  } else {
    document.documentElement.setAttribute('data-theme', 'light');
    localStorage.setItem('theme', 'light');
  }
}

toggleSwitch.addEventListener('change', switchTheme, false);

</script>