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
const facebookSection = document.querySelector(".facebook-extract");
const fbIframe = document.querySelector(".facebook-extract > iframe");

const fbObserver = new ResizeObserver((entries) => {
  entries.forEach((entry) => {
    console.log(entry);
    if (entry.contentRect.width < 180) {
      fbIframe.setAttribute("width", 180);
    } else if (entry.contentRect.width > 500) {
      fbIframe.setAttribute("width", 500);
    } else {
      fbIframe.setAttribute("width", entry.contentRect.width);
      fbIframe.setAttribute(
        "src",
        `https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fusmvilleparisisbadminton%2F&tabs=timeline&width=${Math.ceil(
          entry.contentRect.width
        )}&height=1500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=false&appId`
      );
    }

    console.log("width", entry.contentRect.width);
    console.log("height", entry.contentRect.height);
  });
});

fbObserver.observe(facebookSection);
