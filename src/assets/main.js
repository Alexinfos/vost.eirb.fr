window.addEventListener("load", (_) => {
  Array.from(document.getElementsByClassName("subsection-title")).forEach((el) => {
    el.addEventListener("click", (event) => {
      const subsection = event.target.closest('.video-subsection');

      if (subsection.classList.contains("subsection-folded")) {
        subsection.classList.remove("subsection-folded");
      } else {
        subsection.classList.add("subsection-folded");
      }
    })
  });
});