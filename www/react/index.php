<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>React Hello World</title>
  <!-- React & ReactDOM CDN -->
  <script src="https://unpkg.com/react@17/umd/react.development.js"></script>
  <script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js"></script>
  <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
</head>
<body>
  <div id="root"></div>

  <!-- React 코드 -->
  <script type="text/babel">
    function App() {
      return <h1>Hello, React!</h1>;
    }

    ReactDOM.render(<App />, document.getElementById('root'));
  </script>
</body>
</html>
