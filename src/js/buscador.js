document.addEventListener('DOMContentLoaded',function(){
    fecha.onchange=function(){
        window.location=`?fecha=${fecha.value}`;
    } 
});
