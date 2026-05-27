/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",
    "./template-parts/**/*.php",
    "./inc/**/*.php"
  ],
  theme: {
    extend: {
      colors: {
        darkMain: '#040508',
        darkSurface: '#0c0e17',
        goldAccent: '#DFB15B',
        redAccent: '#C13939',
        textMuted: '#969BA9'
      },
      borderRadius: {
        'large': '24px',
        'small': '14px'
      },
      fontFamily: {
        sans: ['IRANYekanXVF', 'IRANYekanX', 'sans-serif']
      }
    }
  },
  plugins: [],
}
