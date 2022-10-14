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

//

// Handle membership page sliders

const sliderBtns = document.querySelectorAll(".slider");
const arrowBtns = document.querySelectorAll(".slider i");

sliderBtns.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    if (e.target.localName == "i") {
      if (!e.target.parentNode.nextElementSibling.style.maxHeight) {
        e.target.parentNode.nextElementSibling.style.maxHeight = "65rem";
        e.target.style.transform = "translateY(-50%) rotate(180deg)";
      } else if (
        e.target.parentNode.nextElementSibling.style.maxHeight == "0px"
      ) {
        e.target.parentNode.nextElementSibling.style.maxHeight = "65rem";
        e.target.style.transform = "translateY(-50%) rotate(180deg)";
      } else {
        e.target.parentNode.nextElementSibling.style.maxHeight = "0px";
        e.target.style.transform = "translateY(-50%) rotate(0)";
      }
    } else {
      if (!e.target.nextElementSibling.style.maxHeight) {
        e.target.nextElementSibling.style.maxHeight = "65rem";
        e.target.children[0].style.transform =
          "translateY(-50%) rotate(180deg)";
      } else if (e.target.nextElementSibling.style.maxHeight == "0px") {
        e.target.nextElementSibling.style.maxHeight = "65rem";
        e.target.children[0].style.transform =
          "translateY(-50%) rotate(180deg)";
      } else {
        e.target.nextElementSibling.style.maxHeight = "0px";
        e.target.children[0].style.transform = "translateY(-50%) rotate(0)";
      }
    }
  });
});

document.addEventListener("click", (e) => console.log(e.target));
