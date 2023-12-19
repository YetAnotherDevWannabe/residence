let residenceQty = residences.length;

// Change the url for each element of each burger menu
let intercept = function (event) {
   let node = null;
   let goToUrl = null;

   for (let index = 0; index < residenceQty; index++) {
      elementResidence = document.querySelector("#residence_" + index);
      elementMenuIcon = document.querySelector("#menu_icon_" + index);
      elementMenuList = document.querySelector("#menu_list_" + index);

      if (elementMenuIcon.contains(event.target)) {
         node = elementMenuIcon.id;
      } else if (elementResidence.contains(event.target)) {
         const currentUrl = window.location.href;
         let resIndex = elementResidence.id.slice(
            elementResidence.id.lastIndexOf("_") + 1
         );
         goToUrl =
            currentUrl.substring(0, currentUrl.lastIndexOf("dashboard/")) +
            "residence/" +
            residences[resIndex].id +
            "/";
      }
   }

   if (node != null) {
      showMenuList(node);
   } else {
      ////TODO: remove console.log and use assign url once the residence 'view' page is working
      console.log(goToUrl);
      // If you click on a residence (not the menu) it redirects you to the correct page.
      //window.location.assign(goToUrl);
   }
};

// Show the menu when you click on the burger icon
function showMenuList(elementId) {
   let residenceIndex = elementId.slice(elementId.lastIndexOf("_") + 1);
   let menuIcon = document.querySelector("#" + elementId);
   let menuList = document.querySelector("#menu_list_" + residenceIndex);

   for (let index = 0; index < residenceQty; index++) {
      if (index != residenceIndex) {
         document.querySelector("#menu_icon_" + index).className =
            "group-hover:opacity-100 opacity-0 h-10 w-10 absolute group/menu transition duration-500";
         document.querySelector("#menu_list_" + index).className =
            "absolute opacity-0 left-[-9999px] transition duration-300";
      }
   }

   if (menuList.classList.contains("toggledClass")) {
      // toggle Off
      menuIcon.className =
         "group-hover:opacity-100 opacity-0 h-10 w-10 absolute group/menu transition duration-500";
      menuList.className =
         "absolute opacity-0 left-[-9999px] transition duration-300";
   } else {
      //toggle On
      menuIcon.className =
         "absolute opacity-100 h-10 w-10 transition duration-500";
      menuList.className =
         "absolute opacity-100 left-9 top-9 transition duration-300 toggledClass";
   }
}
