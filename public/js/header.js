/**
 * Handle mobile navigation
 */
const lineOverlay = document.querySelector(".line");
const navMobile = document.querySelector(".nav-mobile");
const subMenus = document.querySelectorAll(".sub-menu");

menuButton.addEventListener("click", () => {
  if (navMobile.classList.contains("open")) {
    navMobile.classList.remove("open");
    navMobile.classList.add("close");
    subMenus.forEach((item) => item.classList.remove("move-in"));
    // Set overflow to null to disable scroll through the overlay
    document.body.style.overflow = null;
    document.body.children[2].style.overflow = null;
  } else {
    navMobile.classList.add("open");
    navMobile.classList.remove("close");
    document.body.style.overflow = "hidden";
    document.body.children[2].style.overflow = "hidden";
  }
});

lineOverlay.addEventListener("click", () => {
  navMobile.classList.remove("open");
  navMobile.classList.add("close");
  subMenus.forEach((item) => item.classList.remove("move-in"));
  document.body.style.overflow = null;
  document.body.children[2].style.overflow = null;
});

/**
 * Handle overlay's swipe on Mobile device
 */
let touchStartX = 0;
let touchStartY = 0;
let touchEndX = 0;
let touchEndY = 0;

navMobile.addEventListener("touchstart", (e) => {
  touchStartX = e.changedTouches[0].clientX;
  touchStartY = e.changedTouches[0].clientY;
});

navMobile.addEventListener("touchend", (e) => {
  touchEndX = e.changedTouches[0].clientX;
  touchEndY = e.changedTouches[0].clientY;

  if (touchEndY - touchStartY >= 200) {
    navMobile.classList.remove("open");
    navMobile.classList.add("close");
    subMenus.forEach((item) => item.classList.remove("move-in"));
    document.body.style.overflow = null;
    document.body.children[2].style.overflow = null;
  }
});

/**
 *  Handle mobile sub-navigation
 */
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
