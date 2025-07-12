# Shadcn/UI Component Guide

This guide shows you how to use shadcn/ui components effectively in your sales tracker app without any custom CSS.

## 🎨 Available Components

You have these shadcn/ui components installed:

- **Card** - For content containers
- **Button** - For actions and interactions
- **Input** - For text input fields
- **Badge** - For status indicators
- **Avatar** - For user profile pictures
- **Dialog** - For modal dialogs
- **Sheet** - For slide-out panels
- **Dropdown Menu** - For context menus
- **Navigation Menu** - For navigation
- **Sidebar** - For app navigation
- **Tabs** - For tabbed content
- **Checkbox** - For boolean inputs
- **Label** - For form labels
- **Textarea** - For multi-line text
- **Tooltip** - For hover information
- **Breadcrumb** - For navigation breadcrumbs
- **Separator** - For visual dividers
- **Skeleton** - For loading states
- **Collapsible** - For expandable content

## 🎯 Design Principles

### 1. Use CSS Variables
Always use shadcn/ui's CSS variables instead of hardcoded colors:

```vue
<!-- ✅ Good -->
<div class="bg-primary text-primary-foreground">
<p class="text-muted-foreground">

<!-- ❌ Bad -->
<div class="bg-blue-500 text-white">
<p class="text-gray-600">
```

### 2. Leverage Semantic Colors
- `bg-primary` / `text-primary-foreground` - Main actions
- `bg-secondary` / `text-secondary-foreground` - Secondary actions
- `bg-muted` / `text-muted-foreground` - Backgrounds and subtle text
- `bg-accent` / `text-accent-foreground` - Hover states
- `bg-destructive` / `text-destructive-foreground` - Errors and warnings

### 3. Use Built-in Variants
Components have built-in variants that work with the design system:

```vue
<Button variant="default">Primary Action</Button>
<Button variant="secondary">Secondary Action</Button>
<Button variant="outline">Outline Action</Button>
<Button variant="ghost">Ghost Action</Button>
<Button variant="destructive">Delete</Button>
```

## 📝 Common Patterns

### Card Layouts
```vue
<Card>
  <CardHeader>
    <CardTitle>Title</CardTitle>
    <CardDescription>Description</CardDescription>
  </CardHeader>
  <CardContent>
    <!-- Content here -->
  </CardContent>
</Card>
```

### Form Layouts
```vue
<div class="space-y-4">
  <div class="space-y-2">
    <Label for="name">Name</Label>
    <Input id="name" placeholder="Enter name" />
  </div>
  <div class="space-y-2">
    <Label for="description">Description</Label>
    <Textarea id="description" placeholder="Enter description" />
  </div>
  <Button type="submit">Save</Button>
</div>
```

### Data Display
```vue
<div class="space-y-4">
  <div class="flex items-center justify-between p-4 bg-muted rounded-lg">
    <div>
      <p class="text-sm text-muted-foreground">Label</p>
      <p class="text-lg font-semibold">Value</p>
    </div>
    <Badge variant="secondary">Status</Badge>
  </div>
</div>
```

### Navigation
```vue
<nav class="space-y-2">
  <Link 
    href="/dashboard" 
    class="flex items-center space-x-3 p-3 rounded-lg hover:bg-accent transition-colors"
  >
    <Dashboard class="h-5 w-5" />
    <span>Dashboard</span>
  </Link>
</nav>
```

## 🎨 Color Usage

### Primary Actions
```vue
<Button class="bg-primary text-primary-foreground">
  Create Business
</Button>
```

### Secondary Elements
```vue
<div class="bg-muted p-4 rounded-lg">
  <p class="text-muted-foreground">Secondary content</p>
</div>
```

### Status Indicators
```vue
<Badge variant="default">Active</Badge>
<Badge variant="secondary">Pending</Badge>
<Badge variant="destructive">Error</Badge>
```

## 📱 Responsive Design

Use Tailwind's responsive prefixes with shadcn/ui:

```vue
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
  <Card>
    <!-- Content -->
  </Card>
</div>
```

## 🌙 Dark Mode Support

All shadcn/ui components automatically support dark mode through CSS variables. No additional work needed!

## 🔧 Adding New Components

To add new shadcn/ui components:

```bash
npx shadcn-vue@latest add [component-name]
```

Common additions:
- `table` - For data tables
- `select` - For dropdown selects
- `switch` - For toggle switches
- `radio-group` - For radio buttons
- `progress` - For progress bars
- `alert` - For notifications
- `toast` - For toast notifications

## 📋 Best Practices

1. **No Custom CSS** - Use only shadcn/ui components and Tailwind utilities
2. **Consistent Spacing** - Use `space-y-*` and `gap-*` utilities
3. **Semantic Colors** - Always use CSS variables for colors
4. **Accessibility** - shadcn/ui components are built with accessibility in mind
5. **Responsive** - Use responsive utilities for mobile-first design

## 🎯 Example: Business Card

```vue
<Card class="hover:bg-accent transition-colors">
  <CardContent class="p-6">
    <div class="flex items-start justify-between">
      <div class="flex-1">
        <h3 class="text-xl font-bold">{{ business.name }}</h3>
        <p class="text-muted-foreground mt-1">{{ business.description }}</p>
      </div>
      <Badge variant="secondary">{{ business.status }}</Badge>
    </div>
    
    <div class="grid grid-cols-2 gap-4 mt-4">
      <div class="p-3 bg-muted rounded-lg">
        <p class="text-sm text-muted-foreground">Revenue</p>
        <p class="text-lg font-semibold">{{ formatCurrency(business.revenue) }}</p>
      </div>
      <div class="p-3 bg-muted rounded-lg">
        <p class="text-sm text-muted-foreground">Profit</p>
        <p class="text-lg font-semibold">{{ formatCurrency(business.profit) }}</p>
      </div>
    </div>
  </CardContent>
</Card>
```

This approach ensures your app has a consistent, professional look while being maintainable and accessible. 