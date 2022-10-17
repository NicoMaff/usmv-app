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

// --- Open overlay in membership page --- //

// const overlayBtns = document.querySelectorAll(".open-overlay");
// const overlayBackBtns = document.querySelectorAll(".overlay-back-button");

// overlayBtns.forEach((overlayBtn) => {
//   overlayBtn.addEventListener("click", () => {
//     overlayBtn.nextElementSibling.style.transform = "translateX(0)";
//     window.scroll({
//       top: 0,
//       left: 0,
//       behavior: "smooth",
//     });
//   });
// });

// overlayBackBtns.forEach((backBtn) => {
//   backBtn.addEventListener("click", (e) => {
//     backBtn.parentNode.parentNode.style.transform = "translateX(150vw)";
//   });
// });

//

// Handle membership page sliders

const sliderBtns = document.querySelectorAll(".slider");
const arrowBtns = document.querySelectorAll(".slider i");

const sliderBtnsArray = Object.values(sliderBtns);
console.log(sliderBtnsArray);

sliderBtns.forEach((sliderBtn) => {
  sliderBtn.addEventListener("click", (e) => {
    console.log(e);
    const otherBtns = [...sliderBtns].filter(
      (otherBtn) => otherBtn.textContent != sliderBtn.textContent
    );

    window.scroll({
      left: 0,
      top:
        document.documentElement.scrollTop +
        sliderBtn.getBoundingClientRect().top -
        100,
      behavior: "smooth",
    });

    otherBtns.forEach((otherButton) => {
      if (otherButton.nextElementSibling.style.maxHeight != "0px") {
        otherButton.nextElementSibling.style.maxHeight = "0px";
        otherButton.children[0].style.transform = "translateY(-50%) rotate(0)";
      }
    });

    if (!sliderBtn.nextElementSibling.style.maxHeight) {
      sliderBtn.nextElementSibling.style.maxHeight = "65rem";
      sliderBtn.children[0].style.transform = "translateY(-50%) rotate(180deg)";
    } else if (sliderBtn.nextElementSibling.style.maxHeight == "0px") {
      sliderBtn.nextElementSibling.style.maxHeight = "65rem";
      sliderBtn.children[0].style.transform = "translateY(-50%) rotate(180deg)";
    } else {
      sliderBtn.nextElementSibling.style.maxHeight = "0px";
      sliderBtn.children[0].style.transform = "translateY(-50%) rotate(0)";
    }

    // if (e.target.localName == "i") {
    //   if (!e.target.parentNode.nextElementSibling.style.maxHeight) {
    //     e.target.parentNode.nextElementSibling.style.maxHeight = "65rem";
    //     e.target.style.transform = "translateY(-50%) rotate(180deg)";
    //   } else if (
    //     e.target.parentNode.nextElementSibling.style.maxHeight == "0px"
    //   ) {
    //     e.target.parentNode.nextElementSibling.style.maxHeight = "65rem";
    //     e.target.style.transform = "translateY(-50%) rotate(180deg)";
    //   } else {
    //     e.target.parentNode.nextElementSibling.style.maxHeight = "0px";
    //     e.target.style.transform = "translateY(-50%) rotate(0)";
    //   }
    // } else {
    //   if (!e.target.nextElementSibling.style.maxHeight) {
    //     e.target.nextElementSibling.style.maxHeight = "65rem";
    //     e.target.children[0].style.transform =
    //       "translateY(-50%) rotate(180deg)";
    //   } else if (e.target.nextElementSibling.style.maxHeight == "0px") {
    //     e.target.nextElementSibling.style.maxHeight = "65rem";
    //     e.target.children[0].style.transform =
    //       "translateY(-50%) rotate(180deg)";
    //   } else {
    //     e.target.nextElementSibling.style.maxHeight = "0px";
    //     e.target.children[0].style.transform = "translateY(-50%) rotate(0)";
    //   }
    // }
  });
});

document.addEventListener("click", (e) => console.log(e.target));
