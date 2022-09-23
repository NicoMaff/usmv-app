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
