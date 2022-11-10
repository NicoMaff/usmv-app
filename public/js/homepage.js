/**
 * Handle membership page sliders
 */
const sliderBtns = document.querySelectorAll(".slider");
const arrowBtns = document.querySelectorAll(".slider i");

const sliderBtnsArray = Object.values(sliderBtns);

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

/**
 * Handle Facebook plugin width responsive
 */
const facebookSection = document.querySelector(".facebook-extract");
const fbIframe = document.querySelector(".facebook-extract > iframe");

const facebookObserver = new ResizeObserver((entries) => {
  entries.forEach((entry) => {
    if (window.innerWidth <= 320) {
      fbIframe.setAttribute(
        "src",
        `https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fusmvilleparisisbadminton%2F&tabs=timeline&width=249&height=${facebookSection.offsetHeight}&small_header=false&hide_cover=false&show_facepile=false&appId`
      );
    } else if (entry.contentRect.width >= 500) {
      fbIframe.setAttribute(
        "src",
        `https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fusmvilleparisisbadminton%2F&tabs=timeline&width=500&height=${facebookSection.offsetHeight}&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=false&appId`
      );
    } else {
      fbIframe.setAttribute(
        "src",
        `https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fusmvilleparisisbadminton%2F&tabs=timeline&width=${Math.floor(
          entry.contentRect.width
        )}&height=${
          facebookSection.offsetHeight
        }&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=false&appId`
      );
    }
  });
});

facebookObserver.observe(facebookSection);

/**
 * Handle carousel responsive
 */
const newsSection = document.querySelector(".news");
const carousel = document.getElementById("newsCarousel");

const carouselObserver = new ResizeObserver((entries) => {
  entries.forEach((entry) => {
    carousel.style.width = `${entry.contentRect.width * 0.8}px`;
    carousel.style.height = `${entry.contentRect.width * 0.8}px`;
  });
});

carouselObserver.observe(newsSection);
