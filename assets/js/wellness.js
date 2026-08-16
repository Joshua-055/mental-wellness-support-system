document.addEventListener('DOMContentLoaded', () => {
    const moods = document.querySelectorAll('.checkin-mood');
    const selectedMood = document.querySelector('#selectedMood');
    const form = document.querySelector('#wellnessCheckin');
    const moodGroup = document.querySelector('.checkin-moods');
    const checkinCard = document.querySelector('.checkin-main');
    const warning = document.createElement('p');
    warning.className = 'mood-selection-warning';
    warning.setAttribute('role', 'alert');
    warning.setAttribute('aria-live', 'assertive');
    moodGroup?.insertAdjacentElement('beforebegin', warning);
    const initialMood = selectedMood?.value || '';
    moods.forEach((item) => {
        const isInitial = item.dataset.mood === initialMood;
        item.classList.toggle('selected', isInitial);
        item.setAttribute('aria-pressed', String(isInitial));
    });
    moods.forEach((mood) => mood.addEventListener('click', () => {
        moods.forEach((item) => { item.classList.remove('selected'); item.setAttribute('aria-pressed', 'false'); });
        mood.classList.add('selected'); mood.setAttribute('aria-pressed', 'true');
        if (selectedMood) selectedMood.value = mood.dataset.mood;
        moodGroup?.classList.remove('selection-error'); checkinCard?.classList.remove('mood-card-error'); warning.textContent = '';
    }));
    form?.addEventListener('submit', (event) => {
        if (!selectedMood?.value) {
            event.preventDefault();
            warning.textContent = 'Select a mood to continue.';
            moodGroup?.classList.remove('selection-error');
            checkinCard?.classList.remove('mood-card-error');
            void moodGroup?.offsetWidth;
            moodGroup?.classList.add('selection-error');
            checkinCard?.classList.add('mood-card-error');
            warning.scrollIntoView({ behavior: 'smooth', block: 'center' });
            moods[0]?.focus();
            if ('vibrate' in navigator) navigator.vibrate([80, 45, 80]);
        }
    });
    const reflection = document.querySelector('#reflection'); const count = document.querySelector('#characterCount');
    if (reflection && count) reflection.addEventListener('input', () => { count.textContent = reflection.value.length; });
});
