const msg = document.getElementById("reviewMessage");
if (msg) {
  setTimeout(() => {
    msg.style.opacity = "0";
    msg.style.transform = "translateY(-10px)";
  }, 3000);

  setTimeout(() => {
    if (msg.parentNode) msg.parentNode.removeChild(msg);
  }, 3500);
}
