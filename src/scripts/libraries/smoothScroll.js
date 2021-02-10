// Vanilla JavaScript Scroll to Anchor
// @ https://perishablepress.com/vanilla-javascript-scroll-anchor/

function scrollTo() {
  var links = document.querySelectorAll('nav li a, button[href]');
  for (var i = 0; i < links.length; i++) {
    var link = links[i];
        link.onclick = scrollAnchors;
  }
}

function scrollAnchors(e, respond = null) {
  const distanceToTop = el => Math.floor(el.getBoundingClientRect().top);
  e.preventDefault();
  var targetID = (respond) ? respond.getAttribute('href') : this.getAttribute('href');
  const targetAnchor = document.querySelector(targetID);
  if (!targetAnchor) return;
  const originalTop = distanceToTop(targetAnchor);
  window.scrollBy({ top: originalTop, left: 0, behavior: 'smooth' });
  const checkIfDone = setInterval(function () {
    const atBottom = window.innerHeight + window.pageYOffset >= document.body.offsetHeight - 2;
    if (distanceToTop(targetAnchor) === 0 || atBottom) {
      window.history.pushState('', '', targetID);
      clearInterval(checkIfDone);
    }
  }, 100);
}