@extends('layouts.guest')

@section('content')
<div class="min-h-screen w-full relative overflow-hidden bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 flex items-center justify-center">
  <!-- Animated Gradient Orbs -->
  <div class="absolute top-20 left-10 w-72 h-72 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-orb1"></div>
  <div class="absolute top-40 right-10 w-72 h-72 bg-gradient-to-r from-indigo-400 to-cyan-400 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-orb2"></div>
  <div class="absolute -bottom-20 left-1/3 w-72 h-72 bg-gradient-to-r from-pink-400 to-rose-400 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-orb3"></div>

  <!-- Floating Particles -->
  @for ($i = 0; $i < 20; $i++)
      <div class="absolute w-2 h-2 bg-white rounded-full opacity-40 animate-particle"
          style="left: {{ rand(5, 95) }}%; top: {{ rand(5, 95) }}%; animation-delay: {{ rand(0, 20) / 10 }}s;"></div>
  @endfor

  <!-- Login Card -->
  <div class="relative z-10 flex items-center justify-center min-h-screen px-4">
    <div class="w-full max-w-md">
      <!-- Logo/Title Section (container removed, logo only) -->
      <div class="flex flex-col items-center mb-8">
        <span class="inline-flex items-center justify-center w-20 h-20 mb-4 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg text-white text-3xl font-bold">LOGO</span>
        <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Welcome Back</h1>
        <p class="mt-2 text-gray-600">Sign in to continue to your account</p>
      </div>
      <!-- Glassmorphic Login Form Card -->
      <div class="glass-panel-strong rounded-[2rem] p-8 shadow-2xl">
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
          @csrf
          <!-- ID Number Field -->
          <div class="space-y-2">
            <label for="id_number" class="text-gray-700 font-medium">ID Number</label>
            <div class="relative">
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16M4 12c0-4 4-8 8-8s8 4 8 8-4 8-8 8-8-4-8-8z"/></svg>
              <input id="id_number" name="id_number" type="text" inputmode="numeric" pattern="\d{4}" minlength="4" maxlength="4" placeholder="0000" required autofocus autocomplete="off" 
                class="pl-10 h-12 w-full rounded-xl bg-white/50 border border-gray-200 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 placeholder-gray-400 text-gray-700 transition-all duration-300"
                oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,4)" />
              @error('id_number')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <!-- Password Field with Eye Toggle and Forgot Password Link -->
          <div class="space-y-2">
            <label for="password" class="text-gray-700 font-medium">Password</label>
            <div class="relative">
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 0v2m0 4h.01"></path></svg>
              <input id="password" name="password" type="password" placeholder="Enter your password" required autocomplete="current-password"
                class="pl-10 pr-10 h-12 w-full rounded-xl bg-white/50 border border-gray-200 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 placeholder-gray-400 text-gray-700 transition-all duration-300" />
              <button type="button" id="togglePassword" tabindex="-1" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
              </button>
            </div>
            <div class="flex justify-end mt-1">
              <a href="{{ route('password.request') }}" class="text-xs text-purple-600 hover:text-purple-800 transition-colors font-medium">Forgot your password?</a>
            </div>
            @error('password')
              <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
          </div>
          <!-- Submit Button -->
          <button type="submit" class="btn-glass-primary h-12 w-full rounded-2xl text-base font-bold shadow-xl">Sign In</button>
        </form>
        <!-- Sign Up Link -->
        <div class="mt-6 text-center text-sm text-gray-600">
          Don't have an account? <a href="#" class="text-purple-600 hover:text-purple-700 transition-colors font-medium">Sign up for free</a>
        </div>
      </div>
      <!-- Footer -->
      <div class="mt-8 text-center text-xs text-gray-500">
        <p>Protected by enterprise-grade security</p>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const passwordInput = document.getElementById('password');
  const togglePassword = document.getElementById('togglePassword');
  const eyeIcon = document.getElementById('eyeIcon');
  let isVisible = false;
  if (togglePassword && passwordInput && eyeIcon) {
    togglePassword.addEventListener('click', function () {
      isVisible = !isVisible;
      passwordInput.type = isVisible ? 'text' : 'password';
      eyeIcon.innerHTML = isVisible
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.362m1.528-1.522A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.132M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />`
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
    });
  }
});
</script>
@endsection
