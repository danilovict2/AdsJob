function enableSidebar(){
    const dropdown = document.getElementById('sidebar');    
    const dropdownContent = dropdown.querySelector('.sidebar-content')
    dropdownContent.classList.toggle('sidebar-content-open');
}

window.addEventListener("click", event => {
    if(!event.target.classList.contains('sidebarIcon') && !event.target.classList.contains('enableSidebar')){
        const dropdown = document.querySelector('.sidebar-content');
        dropdown.classList.remove("sidebar-content-open");
    }
});