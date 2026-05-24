<script>
(function(){
  document.documentElement.setAttribute('data-theme', 'dark');
  localStorage.setItem('theme', 'dark');
  if (localStorage.getItem('theme-cyber') === 'enabled') {
    document.documentElement.classList.add('theme-cyber');
  }
})();
</script>
