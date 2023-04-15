function enableSidebar(){
    const dropdown = document.getElementById('sidebar');    
    const dropdownContent = dropdown.querySelector('.sidebar-content')
    dropdownContent.classList.toggle('sidebar-content-open');
}
