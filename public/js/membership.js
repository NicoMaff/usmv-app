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
