# Overview

This is a Brazilian professional networking platform that allows users to create accounts, log in, and potentially connect with other professionals. The application focuses on career networking with features for displaying professional status, company information, and job opportunities. It's built as a web application with a clean, modern interface targeting professionals.

# User Preferences

Preferred communication style: Simple, everyday language.

# System Architecture

## Frontend Architecture
- **Static HTML/CSS Frontend**: Uses traditional HTML forms with server-side processing
- **Responsive Design**: Mobile-first approach with viewport meta tags and flexible CSS
- **Form-Based Interaction**: Registration and login handled through HTML forms with POST methods
- **Modern CSS**: Uses system fonts and clean, professional styling with a blue color scheme

## Backend Architecture
- **PHP Server-Side Processing**: Forms submit to PHP scripts in an `auth/` directory
- **Session-Based Authentication**: Likely uses PHP sessions for user management
- **Form Validation**: Client-side required fields with server-side processing
- **MVC-Style Structure**: Separation of concerns with dedicated auth directory for authentication logic

## User Management System
- **Registration Flow**: Comprehensive user signup with professional information
- **Login System**: Email/password authentication
- **Professional Profiles**: Captures company, job title, and professional status
- **Status Tags**: Users can indicate availability for networking opportunities

## Design Patterns
- **Traditional Web Application**: Server-rendered pages with form submissions
- **Progressive Enhancement**: Basic HTML functionality with CSS styling enhancements
- **Semantic HTML**: Proper form structure with labels and accessibility considerations

# External Dependencies

## Core Technologies
- **PHP**: Server-side scripting for authentication and form processing
- **HTML5**: Modern markup with semantic elements
- **CSS3**: Advanced styling with flexbox and modern properties

## Potential Database Integration
- **User Storage**: Likely MySQL or similar database for user accounts and professional information
- **Session Management**: PHP session handling for authentication state

## Browser Compatibility
- **Modern Web Standards**: Uses contemporary CSS properties and HTML5 features
- **Cross-Platform Fonts**: System font stack for optimal rendering across devices

## Development Dependencies
- **Web Server**: Apache or Nginx for serving PHP content
- **PHP Runtime**: Server-side processing environment