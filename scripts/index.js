let optionButton = document.querySelector(".guess-container__options-button")
let sideBarMenu = document.querySelector(".guess-container__sidebar-menu")


optionButton.addEventListener("click", () => {

    sideBarMenu.classList.toggle("guess-container__sidebar-menu--active");

})