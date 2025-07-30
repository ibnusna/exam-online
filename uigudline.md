# Design System UI Ujian Web

### Layout Structure

- **Container**: Max-width 7xl (1280px), flex layout dengan gap 1.5rem
- **Main Content**: Flex-1, background putih, padding 1.5rem, border-radius 0.5rem
- **Sidebar**: Width max 18rem (288px), background putih, padding 1.5rem
- **Responsive**: Column layout mobile, row layout desktop (lg breakpoint)

### Color Palette Analysis

```css
/* Berdasarkan kode existing */
Background: #000000 (black)
Content: #ffffff (white)
Success: #15803d (green-700)
Success Dark: #1f7a4a (green-800)
Danger: #dc2626 (red-600)
Neutral Light: #d1d5db (gray-300)
Neutral Medium: #6b7280 (gray-500)
Text Primary: #111827 (gray-900)
```

---

## CSS Design System Variables

### Color System

```css
:root {
  /* Brand Colors */
  --exam-primary: #000000;
  --exam-content-bg: #ffffff;
  --exam-success: #15803d;
  --exam-success-dark: #1f7a4a;
  --exam-danger: #dc2626;
  --exam-warning: #f59e0b;

  /* Neutral Colors */
  --exam-gray-50: #f9fafb;
  --exam-gray-100: #f3f4f6;
  --exam-gray-300: #d1d5db;
  --exam-gray-500: #6b7280;
  --exam-gray-700: #374151;
  --exam-gray-900: #111827;

  /* Semantic Colors */
  --exam-text-primary: var(--exam-gray-900);
  --exam-text-secondary: var(--exam-gray-500);
  --exam-text-inverse: #ffffff;
  --exam-border: var(--exam-gray-300);
}
```

### Typography System

```css
:root {
  /* Font Sizes */
  --exam-text-xs: 0.75rem; /* 12px */
  --exam-text-sm: 0.875rem; /* 14px */
  --exam-text-base: 1rem; /* 16px */
  --exam-text-lg: 1.125rem; /* 18px */

  /* Font Weights */
  --exam-font-normal: 400;
  --exam-font-medium: 500;
  --exam-font-semibold: 600;
  --exam-font-bold: 700;

  /* Line Heights */
  --exam-leading-tight: 1.25;
  --exam-leading-normal: 1.5;
  --exam-leading-relaxed: 1.625;
}
```

### Spacing System

```css
:root {
  /* Spacing Scale */
  --exam-space-1: 0.25rem; /* 4px */
  --exam-space-2: 0.5rem; /* 8px */
  --exam-space-3: 0.75rem; /* 12px */
  --exam-space-4: 1rem; /* 16px */
  --exam-space-6: 1.5rem; /* 24px */
  --exam-space-8: 2rem; /* 32px */
  --exam-space-12: 3rem; /* 48px */
  --exam-space-16: 4rem; /* 64px */

  /* Component Specific */
  --exam-container-padding: var(--exam-space-6);
  --exam-content-padding: var(--exam-space-6);
  --exam-element-gap: var(--exam-space-6);
}
```

### Component Dimensions

```css
:root {
  /* Layout */
  --exam-container-max: 112rem; /* 1792px */
  --exam-sidebar-width: 18rem; /* 288px */
  --exam-sidebar-width-mobile: 100%;

  /* Border Radius */
  --exam-radius-sm: 0.25rem; /* 4px */
  --exam-radius-md: 0.5rem; /* 8px */
  --exam-radius-lg: 0.75rem; /* 12px */
  --exam-radius-full: 9999px; /* Circle */

  /* Question Buttons */
  --exam-btn-size: 1.75rem; /* 28px */
  --exam-btn-font-size: 9px;
  --exam-btn-radius: var(--exam-radius-full);

  /* Shadows */
  --exam-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  --exam-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}
```

### Interactive States

```css
:root {
  /* Transitions */
  --exam-transition-fast: 150ms ease-in-out;
  --exam-transition-normal: 250ms ease-in-out;
  --exam-transition-slow: 350ms ease-in-out;

  /* Hover States */
  --exam-hover-opacity: 0.8;
  --exam-hover-scale: 1.05;
  --exam-hover-translate: -1px;

  /* Focus States */
  --exam-focus-ring: 2px solid var(--exam-success);
  --exam-focus-offset: 2px;
}
```

---

## Component Specifications

### 1. Timer Badge

```css
.exam-timer {
  background: var(--exam-success);
  color: var(--exam-text-inverse);
  padding: var(--exam-space-2) var(--exam-space-4);
  border-radius: var(--exam-radius-md);
  font-size: var(--exam-text-sm);
  font-weight: var(--exam-font-semibold);
  display: flex;
  align-items: center;
  gap: var(--exam-space-2);
}
```

### 2. Question Navigation Buttons

```css
.exam-question-btn {
  width: var(--exam-btn-size);
  height: var(--exam-btn-size);
  border-radius: var(--exam-btn-radius);
  font-size: var(--exam-btn-font-size);
  font-weight: var(--exam-font-semibold);
  border: none;
  cursor: pointer;
  transition: all var(--exam-transition-fast);
}

/* Button States */
.exam-question-btn--answered {
  background: var(--exam-success);
  color: var(--exam-text-inverse);
}

.exam-question-btn--unanswered {
  background: var(--exam-gray-300);
  color: var(--exam-gray-700);
}

.exam-question-btn--current {
  background: var(--exam-danger);
  color: var(--exam-text-inverse);
}
```

### 3. Layout Components

```css
.exam-container {
  max-width: var(--exam-container-max);
  margin: 0 auto;
  padding: var(--exam-container-padding);
  display: flex;
  gap: var(--exam-element-gap);
}

.exam-content {
  flex: 1;
  background: var(--exam-content-bg);
  padding: var(--exam-content-padding);
  border-radius: var(--exam-radius-md);
  box-shadow: var(--exam-shadow-sm);
}

.exam-sidebar {
  width: var(--exam-sidebar-width);
  background: var(--exam-content-bg);
  padding: var(--exam-content-padding);
  border-radius: var(--exam-radius-md);
  box-shadow: var(--exam-shadow-sm);
}
```

### 4. Question Grid

```css
.exam-question-grid {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: var(--exam-space-1);
}

/* Responsive Grid */
@media (max-width: 1024px) {
  .exam-question-grid {
    grid-template-columns: repeat(5, 1fr);
    gap: var(--exam-space-2);
  }
}

@media (max-width: 640px) {
  .exam-question-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
```

---

## Responsive Breakpoints

```css
:root {
  /* Breakpoints */
  --exam-breakpoint-sm: 640px;
  --exam-breakpoint-md: 768px;
  --exam-breakpoint-lg: 1024px;
  --exam-breakpoint-xl: 1280px;
}

/* Mobile First Approach */
@media (min-width: 640px) {
  /* sm */
}
@media (min-width: 768px) {
  /* md */
}
@media (min-width: 1024px) {
  /* lg */
}
@media (min-width: 1280px) {
  /* xl */
}
```

---

## Utility Classes System

```css
/* Spacing Utilities */
.exam-p-1 {
  padding: var(--exam-space-1);
}
.exam-p-2 {
  padding: var(--exam-space-2);
}
.exam-p-4 {
  padding: var(--exam-space-4);
}
.exam-p-6 {
  padding: var(--exam-space-6);
}

.exam-m-1 {
  margin: var(--exam-space-1);
}
.exam-m-2 {
  margin: var(--exam-space-2);
}
.exam-m-4 {
  margin: var(--exam-space-4);
}
.exam-m-6 {
  margin: var(--exam-space-6);
}

/* Text Utilities */
.exam-text-xs {
  font-size: var(--exam-text-xs);
}
.exam-text-sm {
  font-size: var(--exam-text-sm);
}
.exam-text-base {
  font-size: var(--exam-text-base);
}

.exam-font-normal {
  font-weight: var(--exam-font-normal);
}
.exam-font-semibold {
  font-weight: var(--exam-font-semibold);
}

/* Color Utilities */
.exam-text-primary {
  color: var(--exam-text-primary);
}
.exam-text-secondary {
  color: var(--exam-text-secondary);
}
.exam-bg-success {
  background-color: var(--exam-success);
}
.exam-bg-danger {
  background-color: var(--exam-danger);
}

/* Layout Utilities */
.exam-flex {
  display: flex;
}
.exam-grid {
  display: grid;
}
.exam-hidden {
  display: none;
}
.exam-rounded {
  border-radius: var(--exam-radius-md);
}
.exam-shadow {
  box-shadow: var(--exam-shadow-sm);
}
```

---

## Implementation Guidelines

### 1. Konsistensi Desain

- Gunakan variabel CSS untuk semua nilai
- Pertahankan proporsi dan spacing yang sudah ditetapkan
- Ikuti sistem warna yang telah didefinisikan

### 2. Responsivitas

- Mobile-first approach
- Breakpoint konsisten di semua komponen
- Grid system yang fleksibel untuk berbagai ukuran layar

### 3. Aksesibilitas

- Focus states yang jelas
- Contrast ratio minimal 4.5:1
- Keyboard navigation support

### 4. Performance

- Minimal CSS dengan variabel yang efisien
- Transition yang smooth namun tidak berlebihan
- Lazy loading untuk komponen besar

Sistem ini dapat digunakan sebagai foundation untuk semua tampilan ujian dengan konsistensi visual dan kode yang maintainable.
