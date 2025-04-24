const token = localStorage.getItem('token');
const navbar = document.querySelector(".fixed.top-0"); // navbar utama
function logout(){
  fetch('http://localhost/santapin-api/auth/logout.php', {
      method: 'POST',
      headers: {
          'Authorization': 'Bearer ' + token
      }
  })
  .then(res => res.json())
  .then(result => {
      if (result.status) {
          localStorage.clear(); 
          window.location.href = '/login/login.html';
      } else {
          alert('Logout gagal: ' + result.message);
      }
  });

}

function back(){
    window.history.back();
}
      // Highlight nav aktif berdasarkan URL
      const navLinks = document.querySelectorAll("nav a");
      navLinks.forEach(link => {
        const current = window.location.pathname;
        if (link.getAttribute("href") === current) {
          link.classList.remove("text-white");
          link.classList.add("text-[#B8860B]");
        } else {
          link.classList.remove("text-[#B8860B]");
          link.classList.add("text-white");
        }
      });