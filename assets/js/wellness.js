document.addEventListener("DOMContentLoaded", () => {
  const moods = document.querySelectorAll(".checkin-mood");
  const selectedMood = document.querySelector("#selectedMood");
  moods.forEach((mood) =>
    mood.addEventListener("click", () => {
      moods.forEach((item) => {
        item.classList.remove("selected");
        item.setAttribute("aria-pressed", "false");
      });
      mood.classList.add("selected");
      mood.setAttribute("aria-pressed", "true");
      if (selectedMood) selectedMood.value = mood.dataset.mood;
    }),
  );
  const reflection = document.querySelector("#reflection");
  const count = document.querySelector("#characterCount");
  if (reflection && count)
    reflection.addEventListener("input", () => {
      count.textContent = reflection.value.length;
    });
});
