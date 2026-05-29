<script>
(function(){
var btn=document.getElementById('themeToggle');
if(!btn)return;
btn.addEventListener('click',function(){
var r=document.documentElement,d=r.getAttribute('data-theme')==='dark';
if(d){r.removeAttribute('data-theme');localStorage.setItem('theme','light');}
else{r.setAttribute('data-theme','dark');localStorage.setItem('theme','dark');}
});
})();
</script>
