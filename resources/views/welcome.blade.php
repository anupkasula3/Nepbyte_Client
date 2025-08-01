<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial‑scale=1.0" />
  <title>Nepbyte - Leading IT Solutions & Digital Innovation</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          animation: {
            'float': 'float 6s ease‑in‑out infinite',
            'glow': 'glow 2s ease‑in‑out infinite alternate',
            'slide‑up': 'slideUp 0.8s ease‑out',
            'fade‑in': 'fadeIn 1s ease‑out',
            'pulse‑slow': 'pulse 3s cubic‑bezier(0.4,0,0.6,1) infinite'
          },
          keyframes: {
            float: {
              '0%, 100%': { transform: 'translateY(0px)' },
              '50%': { transform: 'translateY(-20px)' }
            },
            glow: {
              '0%': { boxShadow: '0 0 20px rgba(59,130,246,0.5)' },
              '100%': { boxShadow: '0 0 40px rgba(59,130,246,0.8)' }
            },
            slideUp: {
              '0%': { transform: 'translateY(100px)', opacity: '0' },
              '100%': { transform: 'translateY(0)', opacity: '1' }
            },
            fadeIn: {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' }
            }
          }
        }
      }
    };
  </script>
  <style>
    .gradient-text {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .glass-effect {
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .tech-grid {
      background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.15) 1px, transparent 0);
      background-size: 20px 20px;
    }
    .hero-bg {
      background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
    }
  </style>
</head>
<body class="hero-bg text-white overflow-x-hidden">
  <!-- Navigation -->
  <nav class="fixed w-full z-50 glass-effect bg-black/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center">
          <img src="https://nep-byte.vercel.app/images/logo/main.png" alt="Nepbyte Logo" class="w-48 h-auto mr-3" />

        </div>
        <div class="hidden md:block">
          <div class="ml-10 flex items-baseline space-x-8">
            <a href="#home" class="hover:text-blue-400 transition-colors duration-300">Home</a>
            <a href="#services" class="hover:text-blue-400 transition-colors duration-300">Services</a>
            <a href="#about" class="hover:text-blue-400 transition-colors duration-300">About</a>
            <a href="#contact" class="hover:text-blue-400 transition-colors duration-300">Contact</a>
          </div>
        </div>
        <button class="md:hidden text-white" aria-label="Toggle navigation menu">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
      <div id="mobile-menu" class="md:hidden hidden flex-col space-y-4 mt-4 bg-black/80 text-white p-4 rounded-lg">
        <a href="#home" class="hover:text-blue-400">Home</a>
        <a href="#services" class="hover:text-blue-400">Services</a>
        <a href="#about" class="hover:text-blue-400">About</a>
        <a href="#contact" class="hover:text-blue-400">Contact</a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section id="home" class="min-h-screen flex items-center justify-center relative tech-grid">
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute top-20 left-10 w-20 h-20 bg-blue-500/20 rounded-full animate-float" style="animation-delay:0s;"></div>
      <div class="absolute top-40 right-20 w-16 h-16 bg-purple-500/20 rounded-lg animate-float" style="animation-delay:1s;"></div>
      <div class="absolute bottom-40 left-20 w-12 h-12 bg-cyan-500/20 rounded-full animate-float" style="animation-delay:2s;"></div>
      <div class="absolute bottom-20 right-10 w-24 h-24 bg-indigo-500/20 rounded-lg animate-float" style="animation-delay:3s;"></div>
    </div>
    <div class="text-center px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto relative z-10">
      <h1 class="text-6xl md:text-8xl font-bold mb-6 animate-slide-up">
        <span class="gradient-text">Nepbyte</span>
      </h1>
      <p class="text-xl md:text-2xl mb-8 opacity-90 animate-fade-in" style="animation-delay:0.3s;">
        Transforming Ideas into Digital Reality
      </p>
      <p class="text-lg md:text-xl mb-12 max-w-3xl mx-auto leading-relaxed opacity-80 animate-fade-in" style="animation-delay:0.6s;">
        We are Nepal's premier IT company, specializing in cutting-edge web development, mobile applications, AI solutions, and digital transformation services that propel your business into the future.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in" style="animation-delay:0.9s;">
        <button class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full font-semibold text-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 animate-glow">
          Get Started
        </button>
        <button class="px-8 py-4 border-2 border-white/30 rounded-full font-semibold text-lg hover:bg-white/10 transform hover:scale-105 transition-all duration-300 glass-effect">
          View Portfolio
        </button>
      </div>
    </div>
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
      <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M19 14l-7 7m0 0l-7-7m7 7V3">
        </path>
      </svg>
    </div>
  </section>

  <!-- Services Section -->
  <section id="services" class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-900 to-slate-800">
    <div class="max-w-7xl mx-auto">
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-bold mb-4 gradient-text">Our Services</h2>
        <p class="text-xl opacity-80 max-w-2xl mx-auto">Comprehensive IT solutions tailored to elevate your business</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- repeat each service card as shown -->
        <div class="glass-effect bg-white/5 p-8 rounded-2xl hover:bg-white/10 transition-all duration-300 transform hover:-translate-y-2 group">
          <div class="text-4xl mb-4 group-hover:animate-pulse">🚀</div>
          <h3 class="text-2xl font-bold mb-4 text-blue-400">Web Development</h3>
          <p class="opacity-80 leading-relaxed">Custom websites and web applications built with modern technologies like React, Next.js, and more.</p>
        </div>
        <div class="glass-effect bg-white/5 p-8 rounded-2xl hover:bg-white/10 transition-all duration-300 transform hover:-translate-y-2 group">
          <div class="text-4xl mb-4 group-hover:animate-pulse">📱</div>
          <h3 class="text-2xl font-bold mb-4 text-purple-400">Mobile Apps</h3>
          <p class="opacity-80 leading-relaxed">Native and cross-platform mobile applications for iOS and Android with exceptional UX.</p>
        </div>
        <div class="glass-effect bg-white/5 p-8 rounded-2xl hover:bg-white/10 transition-all duration-300 transform hover:-translate-y-2 group">
          <div class="text-4xl mb-4 group-hover:animate-pulse">🤖</div>
          <h3 class="text-2xl font-bold mb-4 text-cyan-400">AI Solutions</h3>
          <p class="opacity-80 leading-relaxed">Intelligent automation, machine learning, and AI-powered apps to streamline operations.</p>
        </div>
        <div class="glass-effect bg-white/5 p-8 rounded-2xl hover:bg-white/10 transition-all duration-300 transform hover:-translate-y-2 group">
          <div class="text-4xl mb-4 group-hover:animate-pulse">☁️</div>
          <h3 class="text-2xl font-bold mb-4 text-green-400">Cloud Services</h3>
          <p class="opacity-80 leading-relaxed">Scalable cloud infrastructure, migrations, and DevOps solutions.</p>
        </div>
        <div class="glass-effect bg-white/5 p-8 rounded-2xl hover:bg-white/10 transition-all duration-300 transform hover:-translate-y-2 group">
          <div class="text-4xl mb-4 group-hover:animate-pulse">🎨</div>
          <h3 class="text-2xl font-bold mb-4 text-pink-400">UI/UX Design</h3>
          <p class="opacity-80 leading-relaxed">Beautiful, intuitive designs that engage users and drive results.</p>
        </div>
        <div class="glass-effect bg-white/5 p-8 rounded-2xl hover:bg-white/10 transition-all duration-300 transform hover:-translate-y-2 group">
          <div class="text-4xl mb-4 group-hover:animate-pulse">🔧</div>
          <h3 class="text-2xl font-bold mb-4 text-orange-400">IT Consulting</h3>
          <p class="opacity-80 leading-relaxed">Strategic technology guidance to optimize digital infrastructure.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-blue-900/20 to-purple-900/20">
    <div class="max-w-7xl mx-auto">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        <div class="transform hover:scale-110 transition-transform duration-300">
          <div class="text-4xl md:text-5xl font-bold text-blue-400 mb-2 animate-pulse-slow">500+</div>
          <div class="text-lg opacity-80">Projects Completed</div>
        </div>
        <div class="transform hover:scale-110 transition-transform duration-300">
          <div class="text-4xl md:text-5xl font-bold text-purple-400 mb-2 animate-pulse-slow">200+</div>
          <div class="text-lg opacity-80">Happy Clients</div>
        </div>
        <div class="transform hover:scale-110 transition-transform duration-300">
          <div class="text-4xl md:text-5xl font-bold text-cyan-400 mb-2 animate-pulse-slow">50+</div>
          <div class="text-lg opacity-80">Team Members</div>
        </div>
        <div class="transform hover:scale-110 transition-transform duration-300">
          <div class="text-4xl md:text-5xl font-bold text-green-400 mb-2 animate-pulse-slow">24/7</div>
          <div class="text-lg opacity-80">Support</div>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
          <h2 class="text-4xl md:text-5xl font-bold mb-6 gradient-text">Why Choose Nepbyte?</h2>
          <p class="text-lg opacity-80 mb-6 leading‑relaxed">
            Based in the heart of Nepal, we combine global standards with local expertise to deliver exceptional IT solutions. Our team of passionate developers, designers, and strategists work tirelessly to bring your vision to life.
          </p>
          <div class="space-y-4">
            <div class="flex items-center space-x-3"><div class="w-2 h-2 bg-blue-400 rounded-full"></div><span>Cutting‑edge technology stack</span></div>
            <div class="flex items-center space-x-3"><div class="w-2 h-2 bg-purple-400 rounded-full"></div><span>Agile development methodology</span></div>
            <div class="flex items-center space-x-3"><div class="w-2 h-2 bg-cyan-400 rounded-full"></div><span>24/7 support and maintenance</span></div>
            <div class="flex items-center space-x-3"><div class="w-2 h-2 bg-green-400 rounded-full"></div><span>Competitive pricing</span></div>
          </div>
        </div>
        <div class="relative">
          <div class="glass-effect bg-gradient-to-br from-blue-500/20 to-purple-500/20 p-8 rounded-3xl transform hover:rotate-1 transition-transform duration-500">
            <div class="text-6xl mb-4 text-center">🏆</div>
            <h3 class="text-2xl font-bold text-center mb-4">Award Winning</h3>
            <p class="text-center opacity-80">Recognized for excellence in digital innovation and client satisfaction across Nepal and beyond.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="contact" class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-900 to-slate-800">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="text-4xl md:text-5xl font-bold mb-6 gradient-text">Ready to Start Your Project?</h2>
      <p class="text-xl opacity-80 mb-12 max-w-2xl mx-auto leading‑relaxed">
        Let's discuss how we can transform your ideas into powerful digital solutions. Get in touch with our team today.
      </p>
      <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mb-12">
        <div class="glass-effect bg-white/5 p-6 rounded-2xl flex items-center space‑x-4">
          <div class="text-2xl">📧</div>
          <div><div class="font-semibold">Email</div><div class="opacity-80">hello@nepbyte.com</div></div>
        </div>
        <div class="glass-effect bg-white/5 p-6 rounded-2xl flex items-center space‑x-4">
          <div class="text-2xl">📞</div>
          <div><div class="font-semibold">Phone</div><div class="opacity-80">+977‑1‑NEPBYTE</div></div>
        </div>
      </div>
      <button class="px-12 py-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full font-semibold text-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 animate-glow">
        Start Your Project
      </button>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-12 px-4 sm:px-6 lg:px-8 bg-black/40 border-t border-white/10">
    <div class="max-w-7xl mx-auto">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="md:col-span-2"><div class="text-3xl font-bold gradient-text mb-4">Nepbyte</div>
          <p class="opacity-80 max-w-md">Leading IT company in Nepal, delivering innovative digital solutions that drive business growth and success.</p>
        </div>
        <div><h4 class="font-semibold mb-4">Services</h4>
          <div class="space-y-2 opacity-80"><div>Web Development</div><div>Mobile Apps</div><div>AI Solutions</div><div>Cloud Services</div></div>
        </div>
        <div><h4 class="font-semibold mb-4">Company</h4>
          <div class="space-y-2 opacity-80"><div>About Us</div><div>Careers</div><div>Blog</div><div>Contact</div></div>
        </div>
      </div>
      <div class="border-t border-white/10 mt‑12 pt‑8 text-center opacity-60">
        <p>&copy; 2025 Nepbyte. All rights reserved. Crafted with ❤️ in Nepal</p>
      </div>
    </div>
  </footer>

  <script>
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });

    window.addEventListener('scroll', () => {
      const nav = document.querySelector('nav');
      if (window.scrollY > 100) nav.classList.add('bg-black/40');
      else nav.classList.remove('bg-black/40');
    });

    document.querySelector('button[aria-label="Toggle navigation menu"]').addEventListener('click', () => {
      document.getElementById('mobile-menu').classList.toggle('hidden');
    });

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-slide-up');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('section').forEach(sec => observer.observe(sec));
  </script>
</body>
</html>
