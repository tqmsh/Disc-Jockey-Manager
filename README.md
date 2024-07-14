# Disc Jockey Manager
A software developed by Tianqin Meng during his COOP at Digitera
## Back-end Overview

**FTP:** Coming soon

**Goal:** 
- Develop the Disc Jockey Manager web application & portals
- Create Disc Jockey Manager mobile apps

**Framework:** Laravel Orchid

**GitHub:** Coming soon

## Project Overview

### Applications
- **Super Admin PHP Web Application:** (Coming soon)
- **Local Admin PHP Web Application:** Portal for local DJ companies
- **Client PHP Web Application:** Client Portal (Coming soon)
- **iOS/Android Apps:** For clients (Coming soon)

### Competition Report
**DJ Event Planner:** [Report Link](https://docs.google.com/document/d/1USJCOzn2YbVJIIV2Aa7CwRKMhGIdDjQy3cg1xIMI5JA/edit)

## Local Admin PHP Web Application

### Left-Side Menu
1. Dashboard
2. Events
3. Clients
4. Services
5. Resources
6. Systems
7. Venues
8. Staff
9. Songs

### Dashboard Page
- Primarily for Metrics & Reminders (To be discussed)

### Events Page
- Fields for Add/Edit/Display a Client:
  - Custom Event Name
  - Client (choose from existing Clients)
  - Event Date
  - Event Load-in Time
  - Event Start Date
  - Event End Date
  - Venue (choose from existing Venues)
  - Staff (choose from existing Staff members, multiple selections)
  - System (choose from existing Systems)
  - Song List (choose from available Song Lists)

### Clients Page
- Fields for Add/Edit/Display a Client:
  - First Name
  - Last Name
  - Event Date
  - Event Type (Wedding, Corporate, Mitzvah, School, Private)
  - Number of Expected Guests
  - Venue (from Venues table)

### Services Page
- Fields for Add/Edit/Display a Service:
  - Name of Package
  - Service Type (Wedding, Corporate, Mitzvah, School, Private)
  - Description
  - Price

### Addons Page
- Fields for Add/Edit/Display an Addon:
  - Name of Package
  - Addon Type (e.g., Photobooth, Dancing On A Cloud, Sparklers)
  - Description
  - Price

### Resources Page
- Fields for Add/Edit/Display a Resource:
  - Custom Name
  - Gear Type (e.g., Controller, Amplifier, Speaker, Lighting)
  - Purchase Date
  - Purchased New or Used
  - Start-of-Life Date (if purchased used)

### Systems Page
- Fields for Add/Edit/Display a System:
  - (To be discussed)

### Venues Page
- Fields for Add/Edit/Display a Venue:
  - Name of Venue
  - Address
  - City
  - State/Province
  - Country
  - Zip/Postal
  - Website
  - Contact First Name
  - Contact Last Name
  - Email
  - Phone

### Staff Page
- Fields for Add/Edit/Display a Staff Person:
  - First Name
  - Last Name
  - Position (Dropdown: DJ, MC, Attendant, Tech, Dancer, Roadie)
  - Gender (Male/Female)
  - Email
  - Cell
  - Age

### Song List Page
- Fields for Add/Edit/Display a Song List:
  - List Name
  - Client (pull from existing Clients)
  - Button to see songs requested by the client's guests

## Client Portal PHP Web Application
- Coming soon

## iOS/Android Mobile Apps for Clients
- Coming soon

---

## Old Notes (For Context and Reference)
Here’s a brief overview of what we’re trying to accomplish with Disc Jockey Manager. This is a Business Management Tool for DJs, not a DJ booking portal for the general public.

### Admin Portal Sections
- **CLIENT PORTAL:** For Clients to adjust event information.
- **STAFF PORTAL:** For staff to receive event information.
- **VENUE PORTAL:** For Venue contacts to provide venue details.
- **SPONSOR PORTAL:** For sponsors to upload campaign info and make payments.
- **SUPER-ADMIN PORTAL:** For platform management needs.

### Left-Side Menu Items
Displayed items:
- Events
- Clients
- Packages
- Resources
- Systems
- Venues
- Staff
- Songs

---

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

