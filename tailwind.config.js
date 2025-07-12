/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: [
          'Inter',
          'system-ui',
          'Segoe UI',
          'Roboto',
          'Helvetica Neue',
          'Arial',
          'sans-serif',
        ],
      },
      colors: {
        accent: {
          DEFAULT: '#6C47FF', // Revolut purple
          light: '#A084FF',
          dark: '#3B1E6D',
        },
        revolutBlue: {
          DEFAULT: '#007AFF',
          light: '#4F8CFF',
          dark: '#0051A8',
        },
        revolutPurple: {
          DEFAULT: '#6C47FF',
          light: '#A084FF',
          dark: '#3B1E6D',
        },
        revolutAccent: '#00E1FF', // For accent buttons
        revolutCard: '#F7F8FA', // Card backgrounds
        revolutSheet: '#FFFFFF', // Sheet backgrounds
        // Apple-like colors
        apple: {
          blue: '#007AFF',
          green: '#34C759',
          orange: '#FF9500',
          red: '#FF3B30',
          purple: '#AF52DE',
          pink: '#FF2D92',
          yellow: '#FFCC02',
          gray: {
            50: '#F9F9F9',
            100: '#F2F2F7',
            200: '#E5E5EA',
            300: '#D1D1D6',
            400: '#C7C7CC',
            500: '#AEAEB2',
            600: '#8E8E93',
            700: '#636366',
            800: '#48484A',
            900: '#1C1C1E',
          }
        }
      },
      backgroundImage: {
        'revolut-gradient': 'linear-gradient(90deg, #007AFF 0%, #00E1FF 50%, #6C47FF 100%)',
        'glass': 'linear-gradient(120deg, rgba(255,255,255,0.7) 40%, rgba(108,71,255,0.08) 100%)',
        'apple-gradient-blue': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'apple-gradient-purple': 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
        'apple-gradient-green': 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
        'apple-gradient-orange': 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
        'gradient-conic': 'conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))',
      },
      boxShadow: {
        card: '0 4px 24px 0 rgba(108, 71, 255, 0.08)',
        sheet: '0 -4px 32px 0 rgba(108, 71, 255, 0.10)',
        'mobile-nav': '0 -2px 24px 0 rgba(0, 122, 255, 0.10)',
        'apple': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
        'apple-lg': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
        'apple-xl': '0 35px 60px -12px rgba(0, 0, 0, 0.25)',
        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.37)',
        'glow': '0 0 20px rgba(59, 130, 246, 0.3)',
        'glow-lg': '0 0 30px rgba(59, 130, 246, 0.6)',
      },
      transitionTimingFunction: {
        'spring': 'cubic-bezier(0.34, 1.56, 0.64, 1)',
        'spring-strong': 'cubic-bezier(0.25, 1.5, 0.5, 1)',
        'spring-bounce': 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',
        'apple': 'cubic-bezier(0.4, 0, 0.2, 1)',
      },
      animation: {
        'slide-in-up': 'slideInUp 0.6s ease-out forwards',
        'slide-in-down': 'slideInDown 0.6s ease-out forwards',
        'slide-in-left': 'slideInLeft 0.6s ease-out forwards',
        'slide-in-right': 'slideInRight 0.6s ease-out forwards',
        'fade-in': 'fadeIn 0.8s ease-out forwards',
        'scale-in': 'scaleIn 0.5s ease-out forwards',
        'pulse-glow': 'pulseGlow 2s ease-in-out infinite',
        'shimmer': 'shimmer 1.5s infinite',
        'bounce-gentle': 'bounceGentle 2s infinite',
        'float': 'float 3s ease-in-out infinite',
        'wiggle': 'wiggle 1s ease-in-out infinite',
      },
      keyframes: {
        slideInUp: {
          '0%': { opacity: '0', transform: 'translateY(30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        slideInDown: {
          '0%': { opacity: '0', transform: 'translateY(-30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        slideInLeft: {
          '0%': { opacity: '0', transform: 'translateX(-30px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' },
        },
        slideInRight: {
          '0%': { opacity: '0', transform: 'translateX(30px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' },
        },
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        scaleIn: {
          '0%': { opacity: '0', transform: 'scale(0.9)' },
          '100%': { opacity: '1', transform: 'scale(1)' },
        },
        pulseGlow: {
          '0%, 100%': { boxShadow: '0 0 20px rgba(59, 130, 246, 0.3)' },
          '50%': { boxShadow: '0 0 30px rgba(59, 130, 246, 0.6)' },
        },
        shimmer: {
          '0%': { transform: 'translateX(-100%)' },
          '100%': { transform: 'translateX(100%)' },
        },
        bounceGentle: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-10px)' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-20px)' },
        },
        wiggle: {
          '0%, 100%': { transform: 'rotate(-3deg)' },
          '50%': { transform: 'rotate(3deg)' },
        },
      },
      backdropBlur: {
        xs: '2px',
      },
      borderRadius: {
        '4xl': '2rem',
        '5xl': '2.5rem',
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
        '128': '32rem',
      },
      fontSize: {
        '2xs': ['0.625rem', { lineHeight: '0.75rem' }],
      },
      screens: {
        'xs': '475px',
        '3xl': '1600px',
        '4xl': '1920px',
      },
      zIndex: {
        '60': '60',
        '70': '70',
        '80': '80',
        '90': '90',
        '100': '100',
      },
    },
  },
  plugins: [
    // Custom plugin for mobile-first utilities
    function({ addUtilities, theme }) {
      const newUtilities = {
        '.touch-manipulation': {
          'touch-action': 'manipulation',
        },
        '.scrollbar-hide': {
          '-ms-overflow-style': 'none',
          'scrollbar-width': 'none',
          '&::-webkit-scrollbar': {
            display: 'none',
          },
        },
        '.scrollbar-thin': {
          'scrollbar-width': 'thin',
          'scrollbar-color': 'rgba(0, 0, 0, 0.2) transparent',
          '&::-webkit-scrollbar': {
            width: '6px',
          },
          '&::-webkit-scrollbar-track': {
            background: 'transparent',
          },
          '&::-webkit-scrollbar-thumb': {
            background: 'rgba(0, 0, 0, 0.2)',
            borderRadius: '3px',
          },
          '&::-webkit-scrollbar-thumb:hover': {
            background: 'rgba(0, 0, 0, 0.3)',
          },
        },
        '.text-balance': {
          'text-wrap': 'balance',
        },
        '.text-pretty': {
          'text-wrap': 'pretty',
        },
        '.bg-glass': {
          background: 'rgba(255, 255, 255, 0.25)',
          'backdrop-filter': 'blur(16px)',
          border: '1px solid rgba(255, 255, 255, 0.18)',
        },
        '.bg-glass-dark': {
          background: 'rgba(0, 0, 0, 0.25)',
          'backdrop-filter': 'blur(16px)',
          border: '1px solid rgba(255, 255, 255, 0.18)',
        },
        '.hover-lift': {
          transition: 'transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
          '&:hover': {
            transform: 'translateY(-4px) scale(1.02)',
            boxShadow: '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
          },
        },
        '.hover-scale': {
          transition: 'transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)',
          '&:hover': {
            transform: 'scale(1.05)',
          },
        },
        '.focus-ring': {
          transition: 'box-shadow 0.2s ease-out',
          '&:focus': {
            outline: 'none',
            boxShadow: '0 0 0 3px rgba(59, 130, 246, 0.5)',
          },
        },
        '.btn-animate': {
          transition: 'all 0.2s cubic-bezier(0.4, 0, 0.2, 1)',
          '&:active': {
            transform: 'scale(0.95)',
          },
        },
        '.input-focus': {
          transition: 'all 0.2s ease-out',
          '&:focus': {
            transform: 'scale(1.02)',
          },
        },
        '.card-hover': {
          transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
          '&:hover': {
            transform: 'translateY(-2px)',
            boxShadow: '0 10px 25px rgba(0, 0, 0, 0.1)',
          },
        },
      }
      addUtilities(newUtilities)
    }
  ],
}
