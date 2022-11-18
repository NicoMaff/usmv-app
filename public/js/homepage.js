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
