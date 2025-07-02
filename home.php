
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sehat Guardian - Smart Elderly Health Management</title>
<script src="https://cdn.tailwindcss.com/3.4.16"></script>
<script>tailwind.config={theme:{extend:{colors:{primary:'#4f46e5',secondary:'#10b981'},borderRadius:{'none':'0px','sm':'4px',DEFAULT:'8px','md':'12px','lg':'16px','xl':'20px','2xl':'24px','3xl':'32px','full':'9999px','button':'8px'}}}}</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
<style>
:where([class^="ri-"])::before { content: "\f3c2"; }
body {
font-family: 'Inter', sans-serif;
background-color: #ffffff;
}
</style>
<style>
:where([class^="ri-"])::before { content: "\f3c2"; }
body {
font-family: 'Inter', sans-serif;
background-color: #f9fafb;
}
.sidebar-link.active {
background-color: rgba(79, 70, 229, 0.1);
color: #4f46e5;
border-left: 3px solid #4f46e5;
}
input[type="range"]::-webkit-slider-thumb {
-webkit-appearance: none;
appearance: none;
width: 24px;
height: 24px;
background: #4f46e5;
border-radius: 50%;
cursor: pointer;
}
.custom-checkbox {
position: relative;
display: inline-block;
width: 24px;
height: 24px;
border: 2px solid #d1d5db;
border-radius: 4px;
transition: all 0.2s;
}
.custom-checkbox.checked {
background-color: #4f46e5;
border-color: #4f46e5;
}
.custom-checkbox.checked::after {
content: "";
position: absolute;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
width: 12px;
height: 12px;
background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='white' stroke-width='3'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M5 13l4 4L19 7' /%3E%3C/svg%3E");
background-size: contain;
background-repeat: no-repeat;
}
.toggle-switch {
position: relative;
display: inline-block;
width: 52px;
height: 28px;
}
.toggle-switch input {
opacity: 0;
width: 0;
height: 0;
}
.toggle-slider {
position: absolute;
cursor: pointer;
top: 0;
left: 0;
right: 0;
bottom: 0;
background-color: #e5e7eb;
transition: .4s;
border-radius: 34px;
}
.toggle-slider:before {
position: absolute;
content: "";
height: 20px;
width: 20px;
left: 4px;
bottom: 4px;
background-color: white;
transition: .4s;
border-radius: 50%;
}
input:checked + .toggle-slider {
background-color: #4f46e5;
}
input:checked + .toggle-slider:before {
transform: translateX(24px);
}
.mood-selector input[type="radio"] {
display: none;
}
.mood-selector label {
cursor: pointer;
transition: transform 0.2s;
}
.mood-selector input[type="radio"]:checked + label {
transform: scale(1.2);
color: #4f46e5;
}
.pill-reminder {
transition: all 0.3s;
}
.pill-reminder:hover {
transform: translateY(-2px);
box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}
.water-progress {
height: 20px;
border-radius: 10px;
background-color: #e5e7eb;
overflow: hidden;
}
.water-progress-bar {
height: 100%;
background-color: #4f46e5;
border-radius: 10px;
transition: width 0.5s;
}
.custom-tab {
transition: all 0.3s;
}
.custom-tab.active {
background-color: #4f46e5;
color: white;
}
</style>
</head>
<body class="min-h-screen bg-white">
<nav class="w-full bg-transparent absolute top-0 left-0 z-10 py-6">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex justify-between items-center">
<div class="flex items-center">
<div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
<span class="text-white font-['Pacifico'] text-2xl">SG</span>
</div>
<h1 class="ml-3 text-2xl font-['Pacifico'] text-white">Sehat Guardian</h1>
</div>
<div class="flex items-center space-x-8">
<a href="#" class="text-white hover:text-white/80 font-medium">About</a>
<a href="#" class="text-white hover:text-white/80 font-medium">Contact</a>
<a href="#" class="text-white hover:text-white/80 font-medium">Support</a>
</div>
</div>
</div>
</nav>
<main class="relative min-h-screen">
<!-- Replace this block inside your homepage.html -->
<div class="absolute inset-0 z-0">
  <img src="https://readdy.ai/api/search-image?query=a%20serene%20and%20heartwarming%20scene%20of%20a%20guardian%20angel%20figure%20gently%20helping%20and%20supporting%20an%20elderly%20person%2C%20soft%20ethereal%20lighting%2C%20warm%20and%20comforting%20atmosphere%2C%20modern%20medical%20environment%2C%20high%20end%203d%20rendering%2C%20professional%20lighting%2C%20cinematic%20composition&width=1920&height=1080&seq=hero1&orientation=landscape" alt="Hero Background" class="w-full h-full object-cover">
  <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-black/30"></div>
</div>

<div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-20">
<div class="max-w-3xl mx-auto text-center text-white mb-20">
<h1 class="text-6xl font-bold mb-8">Welcome to Sehat Guardian</h1>
<p class="text-xl leading-relaxed">Your trusted companion in elderly healthcare management. We combine advanced technology with compassionate care to ensure the well-being of your loved ones. Experience personalized care that makes a difference.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-5xl mx-auto">
<div class="group bg-white/90 backdrop-blur-sm rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 p-8 text-center cursor-pointer hover:scale-105 transform">
<div class="w-32 h-32 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-8 group-hover:bg-primary/20 transition-colors duration-300">
<i class="ri-user-heart-line text-7xl text-primary"></i>
</div>
<h2 class="text-3xl font-bold text-gray-800 mb-6">Patient</h2>
<a href="users/login_patient.php" class="w-full inline-block text-center bg-primary text-white py-4 px-6 rounded-button text-lg font-medium hover:bg-primary/90 transition-all duration-300 shadow-lg hover:shadow-xl">
  Login as Patient
</a>

</div>
<div class="group bg-white/90 backdrop-blur-sm rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 p-8 text-center cursor-pointer hover:scale-105 transform">
<div class="w-32 h-32 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-8 group-hover:bg-primary/20 transition-colors duration-300">
<i class="ri-stethoscope-line text-7xl text-primary"></i>
</div>
<h2 class="text-3xl font-bold text-gray-800 mb-6">Doctor</h2>
<a href="doctor/login.php" class="w-full inline-block text-center bg-primary text-white py-4 px-6 rounded-button text-lg font-medium hover:bg-primary/90 transition-all duration-300 shadow-lg hover:shadow-xl">
  Login as Doctor
</a>

</button>
</div>
<div class="group bg-white/90 backdrop-blur-sm rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 p-8 text-center cursor-pointer hover:scale-105 transform">
<div class="w-32 h-32 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-8 group-hover:bg-primary/20 transition-colors duration-300">
<i class="ri-admin-line text-7xl text-primary"></i>
</div>
<h2 class="text-3xl font-bold text-gray-800 mb-6">Admin</h2>
<a href="users/login.php" class="w-full inline-block text-center bg-primary text-white py-4 px-6 rounded-button text-lg font-medium hover:bg-primary/90 transition-all duration-300 shadow-lg hover:shadow-xl">
  Login as Admin
</a>

</div>
</div>
</div>
</main>
<footer class="bg-gray-50 py-12">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="text-center">
<div class="flex items-center justify-center mb-4">
<div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
<span class="text-white font-['Pacifico'] text-xl">SG</span>
</div>
<h3 class="ml-3 text-xl font-['Pacifico'] text-gray-800">Sehat Guardian</h3>
</div>
<p class="text-gray-600">&copy; 2025 Sehat Guardian. All rights reserved.</p>
</div>
</div>
</footer>
</body>
</html>