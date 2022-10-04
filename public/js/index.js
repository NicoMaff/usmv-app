// Handle mobile navigation

// const menuButton = document.getElementById(menuButton);
const lineOverlay = document.querySelector(".line");
const navMobile = document.querySelector(".nav-mobile");
const subMenus = document.querySelectorAll(".sub-menu");

menuButton.addEventListener("click", () => {
  if (navMobile.classList.contains("open")) {
    navMobile.classList.remove("open");
    navMobile.classList.add("close");
    subMenus.forEach((item) => item.classList.remove("move-in"));
  } else {
    navMobile.classList.add("open");
    navMobile.classList.remove("close");
  }
});

lineOverlay.addEventListener("click", () => {
  navMobile.classList.remove("open");
  navMobile.classList.add("close");
  subMenus.forEach((item) => item.classList.remove("move-in"));
});

// ---

// Handle mobile sub-navigation

const backButtons = document.querySelectorAll(".sub-menu-header i");
const tabs = document.querySelectorAll(".tab");

tabs.forEach((tab) => {
  tab.addEventListener("click", (e) =>
    e.target.children[0].classList.add("move-in")
  );
});

backButtons.forEach((button) => {
  button.addEventListener("click", (e) =>
    e.target.parentNode.parentNode.classList.remove("move-in")
  );
});

lineOverlay.addEventListener("drag", (e) => console.log("yes"));
document
  .querySelector(".nav-mobile")
  .addEventListener("drag", (e) => console.log(e));
