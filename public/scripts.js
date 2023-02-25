document.getElementById('admin-icon').addEventListener("click", popupMenu)
function popupMenu() {
    let button = document.getElementById('admin-icon');
    let menu = document.getElementById('admin-menu')
    button.onclick = function () {
        menu.style.setProperty('display', 'flex');
    }
}
window.onclick = function(event) {
    if (!event.target.matches('#admin-icon')) {
        document.getElementById('admin-menu').style.setProperty('display', 'none');
    }
}


let buttonAdd = document.querySelector('.admin-page-a-add');
let formAdd = document.querySelector('.content-add');
let overlay = document.querySelector('.overlay');
buttonAdd.onclick = function Add() {
    formAdd.style.setProperty('display','flex')
    formAdd.style.setProperty('position','absolute')
    overlay.style.setProperty('cursor', 'pointer')
    overlay.style.setProperty('opacity', '0.9')
    overlay.style.setProperty('z-index', '0')
}

window.onclick = function(e){
    if(!e.target.matches('.admin-page-a-add')){
        formAdd.style.setProperty('display','none')
        overlay.style.setProperty('z-index', '-9')
        overlay.style.setProperty('opacity', '0')
    }
}