// --- Handle mobile navigation --- //

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
// --- Handle mobile sub-navigation --- //

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

// ---
// --- Handle membership page sliders --- //

const sliderBtns = document.querySelectorAll(".slider");
const arrowBtns = document.querySelectorAll(".slider i");

const sliderBtnsArray = Object.values(sliderBtns);
// console.log(sliderBtnsArray);

sliderBtns.forEach((sliderBtn) => {
  sliderBtn.addEventListener("click", (e) => {
    console.log(e);
    const otherBtns = [...sliderBtns].filter(
      (otherBtn) => otherBtn.textContent != sliderBtn.textContent
    );

    otherBtns.forEach((otherButton) => {
      if (otherButton.nextElementSibling.style.maxHeight != "0px") {
        otherButton.nextElementSibling.style.maxHeight = "0px";
        otherButton.children[0].style.transform = "translateY(-50%) rotate(0)";
      }
    });

    if (!sliderBtn.nextElementSibling.style.maxHeight) {
      sliderBtn.nextElementSibling.style.maxHeight = "150rem";
      sliderBtn.children[0].style.transform = "translateY(-50%) rotate(180deg)";
    } else if (sliderBtn.nextElementSibling.style.maxHeight == "0px") {
      sliderBtn.nextElementSibling.style.maxHeight = "150rem";
      sliderBtn.children[0].style.transform = "translateY(-50%) rotate(180deg)";
    } else {
      sliderBtn.nextElementSibling.style.maxHeight = "0px";
      sliderBtn.children[0].style.transform = "translateY(-50%) rotate(0)";
    }

    setTimeout(() => {
      window.scroll({
        left: 0,
        top:
          document.documentElement.scrollTop +
          sliderBtn.getBoundingClientRect().top -
          110,
        behavior: "smooth",
      });
    }, 300);
  });
});

//
// --- HANDLE FACEBOOK PLUGIN WIDTH ---
// const facebookSection = document.querySelector(".facebook-extract");
// const fbIframe = document.querySelector(".facebook-extract > iframe");
// const iframeHeight = Math.floor(window.innerHeight - 85);

// console.log(iframeHeight);

// const windowObserver = new ResizeObserver((entries) => {
//   entries.forEach((entry) => {
//     console.log("windowObserver");
//     if (entry.contentRect.width <= 320) {
//       console.log("test1");
//       fbIframe.setAttribute("width", 249);
//       // fbIframe.setAttribute("height", iframeHeight);
//       fbIframe.setAttribute(
//         "src",
//         "https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fusmvilleparisisbadminton%2F&tabs=timeline&width=340&height=331&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId"
//         //`https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fusmvilleparisisbadminton%2F&tabs=timeline&width=249&small_header=false&hide_cover=false&show_facepile=false&appId`
//       );
//     }

//     if (facebookSection.offsetWidth - entry.contentRect.width >= 0) {
//       console.log("test2");
//       // iframe must represent 82,4% of device window width
//       fbIframe.setAttribute(
//         "width",
//         Math.floor(entry.contentRect.width * 0.824)
//       );
//       fbIframe.setAttribute("height", iframeHeight);
//       fbIframe.setAttribute(
//         "src",
//         `https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fusmvilleparisisbadminton%2F&tabs=timeline&width=${Math.floor(
//           entry.contentRect.width * 0.824
//         )}&height=${iframeHeight}&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=false&appId`
//       );
//     }

//     if (window.innerWidth - facebookSection.offsetWidth >= 12.8) {
//       console.log("test5");
//       fbIframe.setAttribute(
//         "width",
//         Math.floor(entry.contentRect.width * 0.824)
//       );
//       fbIframe.setAttribute(
//         "src",
//         "https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fusmvilleparisisbadminton%2F&tabs=timeline&width=340&height=331&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId"
//       );
//     }
//   });
// });

// if (window.innerWidth >= 500) {
//   windowObserver.unobserve(document.body);
// } else {
//   windowObserver.observe(document.body);
// }

// const facebookObserver = new ResizeObserver((entries) => {
//   entries.forEach((entry) => {
//     console.log("facebookObserver");
//     if (entry.contentRect.width >= 500) {
//       console.log("test3");
//       fbIframe.setAttribute("width", 500);
//       fbIframe.setAttribute("height", iframeHeight);
//       fbIframe.setAttribute(
//         "src",
//         `https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fusmvilleparisisbadminton%2F&tabs=timeline&width=500&height=${iframeHeight}&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=false&appId`
//       );
//       // } else {
//       //   console.log("test4");
//       //   fbIframe.setAttribute("width", Math.floor(entry.contentRect.width));
//       //   fbIframe.setAttribute(
//       //     "src",
//       //     `https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fusmvilleparisisbadminton%2F&tabs=timeline&width=${Math.floor(
//       //       entry.contentRect.width
//       //     )}&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=false&appId`
//       //   );
//     }
//   });
// });

// facebookObserver.observe(facebookSection);
