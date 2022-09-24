// Handle mobile navigation

// const menuButton = document.getElementById(menuButton);
const lineOverlay = document.querySelector(".line");
const navMobile = document.querySelector(".nav-mobile");

menuButton.addEventListener("click", () => {
  if (navMobile.classList.contains("open")) {
    navMobile.classList.remove("open");
    navMobile.classList.add("close");
  } else {
    navMobile.classList.add("open");
    navMobile.classList.remove("close");
  }
});

lineOverlay.addEventListener("click", () => {
  navMobile.classList.remove("open");
  navMobile.classList.add("close");
});

// ---

// Handle mobile sub-navigation

const backButton = document.querySelector(".backButton");
const subMenu1 = document.getElementById("subMenu1");
const tab1 = document.querySelector("#tab");

console.log(tab1);

tab1.addEventListener("click", () => {
  console.log("yes");
  if (subMenu1.classList.contains("move-out")) {
    subMenu1.classList.remove("move-out");
    subMenu1.classList.add("move-in");
  } else {
    subMenu1.classList.add("move-in");
  }
});

backButton.addEventListener("click", () => {
  subMenu1.classList.remove("move-in");
  subMenu1.classList.add("move-out");
});
