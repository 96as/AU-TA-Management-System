function toggleDrawer() {
  const drawer = document.getElementById('drawer');
  const content = document.getElementById('main-content');

  if (drawer.classList.contains('drawer-rail')) {
    drawer.classList.remove('drawer-rail');
    drawer.classList.add('drawer-full');
    content.style.marginLeft = '256px';
  } else {
    drawer.classList.remove('drawer-full');
    drawer.classList.add('drawer-rail');
    content.style.marginLeft = '80px';
  }
}

document.addEventListener('DOMContentLoaded', function () {
  const drawer = document.getElementById('drawer');
  drawer.classList.add('drawer-rail');
  document.getElementById('main-content').style.marginLeft = '80px';
});


//Logout alert to be added here
