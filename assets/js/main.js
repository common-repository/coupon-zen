const htCouponShareableLinks = document.querySelectorAll(
  ".couponzen__button-coupon"
);

htCouponShareableLinks.forEach((cuponBtn) => {});

// Tab JS
const tabs = document.querySelector(".couponzen__nav-tabs");
if (tabs) {
  const contents = document.querySelector(tabs.dataset["tabTarget"]);
  const allPanes = contents.querySelectorAll(".couponzen__tab-pane");
  allPanes.forEach((pane) => {
  });
  tabs.addEventListener("click", function (e) {
    const target = e.target;
    const targetTabId = target.dataset["target"];

    if (!targetTabId) return;
    const targetTab = document.querySelector(targetTabId);
    const parent = target.parentElement.parentElement;
    const allButton = parent.querySelectorAll("button");
    allButton.forEach((btn, i) => {
      btn.classList.remove("active");
      allPanes[i].classList.remove("active");
    });
    targetTab.classList.add("active");
    target.classList.add("active");
  });
}
