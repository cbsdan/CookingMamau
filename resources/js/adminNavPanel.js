const body = document.querySelector("body"),
    sidebar= body.querySelector(".sidebar-content"),
    toggle = body.querySelector(".toggle"),
    searchBtn = body.querySelector(".search-box");

toggle.addEventListener("click", () => {
    // Add a small delay before toggling the 'close' class
    setTimeout(() => {
        sidebar.classList.toggle("close");
    }, 50);
});