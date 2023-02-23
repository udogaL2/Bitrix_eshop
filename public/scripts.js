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