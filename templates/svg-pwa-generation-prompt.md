# 🚀 SVG PWA Application Generation Prompt Template

**Prompt template for generating high-quality SVG Progressive Web Applications based on VeriDock Grid standards**

---

## 📋 **UNIVERSAL GENERATION PROMPT**

```
Create a complete SVG Progressive Web Application following VeriDock Grid v2.0 standards. The application must be self-contained in a single SVG file with embedded CSS, JavaScript, and all functionality.

### REQUIREMENTS:
- 100% compliance with SVG PWA Schema v2.0
- Must pass all 21 validation tests
- Self-contained (no external dependencies)
- Responsive design with viewBox
- UTF-8 encoding
- Inline CSS styles only
- Embedded JavaScript for interactivity

### FORBIDDEN ELEMENTS:
- NO <g transform="*"> elements
- NO external CSS/JS references  
- NO PHP code within SVG
- NO foreignObject or switch elements
- NO external image/resource references

### REQUIRED STRUCTURE:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 1200 800">
  <title>[Application Name]</title>
  <desc>[Application Description]</desc>
  
  <defs>
    <style>
      /* All CSS styles inline here */
    </style>
    <!-- Gradients, patterns, reusable elements -->
  </defs>
  
  <!-- Main application content -->
  
  <script><![CDATA[
    // JavaScript functionality here
  ]]></script>
</svg>
```

### APPLICATION TYPE: [SPECIFY TYPE - Dashboard/Calculator/Visualization/etc.]

### SPECIFIC REQUIREMENTS:
[Add specific functional requirements here]
```

---

## 🎨 **PATTERN-SPECIFIC PROMPTS**

### **Dashboard Application Pattern**
*Based on: `correct/devmind.svg`*

```
Create a SVG PWA dashboard application with the following specifications:

LAYOUT REQUIREMENTS:
- Header section with title and navigation
- Card-based information layout (minimum 4 cards)
- Status indicators with color coding
- Interactive buttons with hover effects
- Professional gradient background

FUNCTIONALITY:
- Real-time data display simulation
- Interactive elements with onclick events
- Status toggle functionality
- Responsive card grid layout

CSS REQUIREMENTS:
- Use classes: .header, .card, .text, .value, .button, .button-text
- Implement hover effects: .button:hover
- Color scheme: Dark theme with cyan accents (#64ffda)
- Typography: Arial font family, multiple sizes (12px-24px)

EXAMPLE ELEMENTS TO INCLUDE:
- System status cards
- Performance metrics
- Action buttons
- Progress indicators
- Time/date display

TECHNICAL SPECS:
- ViewBox: 0 0 1200 800
- Responsive design
- Embedded JavaScript for interactions
- All styles in <defs><style> section
```

### **Interactive Calculator Pattern**
*Based on: `correct/test_svg_calculator.svg`*

```
Create a SVG PWA calculator application with the following specifications:

LAYOUT REQUIREMENTS:
- Display screen at top (showing results)
- 4x4 button grid for numbers and operators
- Clear and equals buttons prominently placed
- Compact 300x400 viewport

FUNCTIONALITY:
- Basic arithmetic operations (+, -, *, /)
- Number input handling (0-9)
- Clear function (C)
- Equals calculation (=)
- Error handling for division by zero

CSS REQUIREMENTS:
- Button gradients with hover effects
- Different styling for numbers vs operators
- Display screen with readable font
- Professional color scheme

JAVASCRIPT REQUIREMENTS:
- State management for current operation
- Event handlers for all buttons
- Calculation logic implementation
- Display update functions

TECHNICAL SPECS:
- ViewBox: 0 0 300 400
- All buttons clickable with onclick events
- Gradient definitions in <defs>
- Embedded CDATA JavaScript section
```

### **Data Visualization Pattern**
*Based on: `correct/example.svg`*

```
Create a SVG PWA data visualization application with the following specifications:

LAYOUT REQUIREMENTS:
- Chart/graph display area
- Legend and labels
- Data summary cards
- Interactive elements for data exploration

FUNCTIONALITY:
- Visual data representation (bars, lines, or pie charts)
- Interactive hover effects
- Data filtering/selection
- Animated transitions

METADATA REQUIREMENTS:
- Dublin Core RDF metadata section
- Embedded JSON data store
- Title and description elements
- Version and format information

TECHNICAL SPECS:
- ViewBox: 0 0 1200 800
- Rich metadata in <metadata> section
- Data embedded in SVG structure
- Professional visualization styling
```

---

## 🔧 **VALIDATION CHECKLIST PROMPT**

```
After generating the SVG PWA application, ensure it meets these criteria:

STRUCTURE VALIDATION:
□ XML declaration with UTF-8 encoding
□ SVG root element with proper namespaces  
□ viewBox attribute present
□ width and height defined
□ title and desc elements included

STYLE VALIDATION:
□ All CSS in <defs><style> section
□ No external CSS references
□ Proper class naming conventions
□ Responsive design principles

FUNCTIONALITY VALIDATION:
□ JavaScript in <script><![CDATA[...]]></script>
□ No external JS dependencies
□ Interactive elements with event handlers
□ Error handling implemented

COMPLIANCE VALIDATION:
□ No <g transform="*"> elements
□ No forbidden elements (foreignObject, switch)
□ No external resource references
□ UTF-8 encoding throughout
□ File size under 1MB

TESTING VALIDATION:
□ Must pass: php tester/index.php generated-app.svg
□ Required: 21/21 tests passing (100%)
□ Browser compatibility verified
□ PWA functionality confirmed
```

---

## 📝 **CUSTOM APPLICATION PROMPTS**

### **Business App Template**
```
Create a SVG PWA [BUSINESS_TYPE] application for [SPECIFIC_USE_CASE]:

BUSINESS REQUIREMENTS:
- [List specific business functions]
- [Data management needs]
- [User interaction requirements]
- [Reporting/analytics needs]

TARGET USERS:
- [Primary user type]
- [User expertise level]
- [Usage context]

BRANDING:
- Color scheme: [Specify colors]
- Typography: [Font preferences]
- Visual style: [Modern/classic/minimal]

INTEGRATION:
- PHP backend compatibility
- Data persistence needs
- API integration requirements
```

### **Educational Tool Template**
```
Create a SVG PWA educational tool for [SUBJECT/TOPIC]:

LEARNING OBJECTIVES:
- [Primary learning goal]
- [Secondary objectives]
- [Skill development targets]

INTERACTIVITY:
- [Types of user interactions]
- [Feedback mechanisms]
- [Progress tracking]

CONTENT STRUCTURE:
- [Information presentation]
- [Exercise/practice areas]
- [Assessment components]

ACCESSIBILITY:
- Clear visual hierarchy
- Readable fonts and colors
- Intuitive navigation
- Error prevention/correction
```

---

## 🚀 **USAGE EXAMPLES**

### Example 1: E-commerce Dashboard
```
Create a SVG PWA e-commerce dashboard application following VeriDock Grid standards:

- Sales overview cards showing revenue, orders, customers
- Interactive charts for monthly trends
- Status indicators for inventory levels
- Quick action buttons for common tasks
- Real-time order notifications simulation
- Dark theme with professional styling
- Responsive layout for 1200x800 viewport
```

### Example 2: Time Tracking Tool
```
Create a SVG PWA time tracking application:

- Start/stop timer with visual countdown
- Project selection dropdown
- Time log display with edit capabilities
- Daily/weekly summary cards
- Export functionality simulation
- Modern flat design with color coding
- All functionality embedded in single SVG
```

---

## 🔍 **QUALITY ASSURANCE**

### **Post-Generation Validation**
```bash
# Test generated application
php tester/index.php generated-app.svg

# Expected result: 21/21 tests passing (100%)
# Any failure requires correction
```

### **Manual Review Checklist**
- [ ] Opens correctly in browsers (Chrome, Firefox, Safari)
- [ ] All interactive elements respond to clicks
- [ ] Styles render consistently
- [ ] JavaScript functions without errors
- [ ] Content is readable and well-organized
- [ ] Responsive behavior works properly

---

## 📚 **REFERENCE MATERIALS**

### **Gold Standard Examples**
- **Dashboard:** `correct/devmind.svg` (21/21 tests ✅)
- **Calculator:** `correct/test_svg_calculator.svg` (21/21 tests ✅)  
- **Visualization:** `correct/example.svg` (21/21 tests ✅)

### **Schema Reference**
- **Current Schema:** `tester/svg-pwa-schema-v2.json`
- **Validation Rules:** 21 comprehensive tests
- **Technical Standards:** 100% compliance required

### **Development Tools**
- **CLI Tester:** `php tester/index.php [file.svg]`
- **Web Interface:** `tester/index.html`
- **Documentation:** `README-V2.md`

---

**© 2024 VeriDock Grid v2.0 - SVG PWA Generation Framework**
