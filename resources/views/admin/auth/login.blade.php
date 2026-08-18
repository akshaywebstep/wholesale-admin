<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal | Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS CDN Direct Import -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 min-h-screen flex items-center justify-center p-4 font-sans antialiased">

    <!-- Background Subtle Glow -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"></div>
    </div>

    <!-- Main Card Container -->
    <div class="relative w-full max-w-4xl bg-slate-900/90 border border-slate-800 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row my-8">

        <!-- Left Branding Panel -->
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 text-white p-10 flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold mb-2">Wholesale Admin</h1>
                <p class="text-indigo-100/80 text-sm leading-relaxed">
                    Manage products, inventory, orders, and granular user permissions with a fast, modern control dashboard.
                </p>
            </div>

            <div class="relative z-10 text-xs text-indigo-200/60 mt-8">
                &copy; {{ date('Y') }} Wholesale Portal. All rights reserved.
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="w-full md:w-1/2 p-8 sm:p-10 flex flex-col justify-center bg-slate-900">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-white">Welcome back</h2>
                <p class="text-slate-400 text-sm mt-1">Please enter your credentials to access the admin portal.</p>
            </div>

            @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm p-3.5 rounded-xl mb-5">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-300 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="admin@example.com"
                        class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Password Input with Eye Toggle -->
                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-300 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" id="passwordInput" name="password" required
                            placeholder="••••••••"
                            class="w-full px-4 py-2.5 pr-11 bg-slate-800 border border-slate-700 rounded-xl text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        
                        <button type="button" id="togglePasswordBtn"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-200 transition-colors focus:outline-none">
                            <!-- Eye Open Icon -->
                            <svg id="eyeOpenIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye Closed Icon (Hidden) -->
                            <svg id="eyeClosedIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 013.982-.863c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-1.74 1.74l-11-11" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-slate-400">Remember me</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-semibold transition shadow-lg shadow-indigo-600/30">
                    Sign In to Dashboard
                </button>
            </form>
        </div>

    </div>

    <!-- Toggle Password Script -->
    <script>
        const passwordInput = document.getElementById('passwordInput');
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const eyeOpenIcon = document.getElementById('eyeOpenIcon');
        const eyeClosedIcon = document.getElementById('eyeClosedIcon');

        togglePasswordBtn.addEventListener('click', function() {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            
            eyeOpenIcon.classList.toggle('hidden', isPassword);
            eyeClosedIcon.classList.toggle('hidden', !isPassword);
        });
    </script>

</body>
</html>