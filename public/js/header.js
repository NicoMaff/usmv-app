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

/**
 * Handle overlay's swipe on Mobile device
 */
let touchStartX = 0;
let touchStartY = 0;
let touchEndX = 0;
let touchEndY = 0;

navMobile.addEventListener(
  "touchstart",
  function (event) {
    console.log(event);
    // touchStartX = event.screenX;
    // touchStartY = event.screenY;
    touchStartX = event.changedTouches[0].screenX;
    touchStartY = event.changedTouches[0].screenY;
  },
  false
);

navMobile.addEventListener(
  "touchend",
  function (event) {
    touchEndX = event.changedTouches[0].screenX;
    touchEndY = event.changedTouches[0].screenY;
    handleGesure();
  },
  false
);

function handleGesure() {
  const swiped = "swiped: ";
  if (touchEndX < touchStartX) {
    console.log(swiped + "left!");
  }
  if (touchEndX > touchStartX) {
    console.log(swiped + "right!");
  }
  if (touchEndY < touchStartY) {
    console.log(swiped + "down!");
  }
  if (touchEndY > touchStartY) {
    console.log(swiped + "left!");
  }
  if (touchEndY == touchStartY) {
    console.log("tap!");
  }
}
