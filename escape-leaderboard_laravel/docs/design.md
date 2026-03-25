# Design & Security Overwegingen

## Doel

Leg ontwerpkeuzes vast en motiveer beslissingen met aandacht voor privacy en veiligheid.

## Onderwerpen om te documenteren

- API authenticatie: API-token, rate-limiting, CORS
- Mass-assignment: gebruik van `$fillable` om onbedoelde updates te voorkomen
- Data-retentie: hoe lang worden scores bewaard?
- Privacy: welke persoonsgegevens worden opgeslagen (player_name, IP)? Is anonimiseren nodig?
- Input validatie en sanitatie: guard tegen XSS/SQL-injectie

## Design Documentatie - Escape Room Leaderboard

## Architectuur

### Pattern

- **MVC (Model-View-Controller)**
- **Server-Side Rendering** met Blade templates
- **Multi-layer Security** architecture

### Componenten

- **Backend:** Laravel 10.x met Eloquent ORM
- **Frontend:** Blade templates + Custom CSS
- **Database:** MySQL met relationeel model
- **Security:** Laravel Auth + Custom middleware

## UI Design

### Theme

- **Dark Theme** met glassmorphism effecten
- **Modern & Professional** uitstraling
- **Responsive Design** voor alle apparaten

### Color Palette

```css
:root {
    --primary-purple: #8b5cf6;
    --primary-blue: #3b82f6;
    --text-light: #ffffff;
    --bg-primary: #0a0a0f;
    --glass-bg: rgba(26, 26, 26, 0.85);
}
```

### Key Features

- **Glassmorphism cards** met backdrop-filter
- **Smooth transitions** en hover states
- **Password masking** voor API tokens
- **Mobile-first responsive** design

## User Interface

### Public Leaderboard

- **Game overview** met score tellingen
- **Search functionality** met partial matching
- **Real-time filtering** (JavaScript)
- **Responsive layout** voor mobiel

### Admin Panel

- **Game management** (CRUD operations)
- **Score management** met token validatie
- **Secure authentication** met Laravel Auth
- **Cascade delete** functionaliteit

## Security Design

### Authentication

- **Laravel Auth** voor admin login
- **AdminAuth middleware** voor route protection
- **Session-based** authentication

### API Security

- **Per-game unique tokens** (40-char cryptographically secure)
- **Token validation** per game
- **IP logging** voor audit trails
- **Input validation** met Laravel rules

### Frontend Security

- **CSRF protection** op alle formulieren
- **Password masking** tegen shoulder surfing
- **Autocomplete disabled** voor sensitive fields
- **No persistent storage** van tokens

## Database Design

### Relations

- **Game** hasMany **Score** (1-to-many)
- **Cascade delete** voor data integriteit
- **Eager loading** voor performance

### Indexes

- **Primary keys** op id velden
- **Unique indexes** op name, slug, api_token
- **Foreign key** op game_id
- **Performance indexes** op veelgebruikte queries

## Performance Optimization

### Database

- **Eager loading** om N+1 problemen te voorkomen
- **Query optimization** met proper indexes
- **Cascade delete** voor efficiente data removal

### Frontend

- **CSS optimization** met Vite build
- **Asset minification** voor snellere laadtijden
- **Responsive images** en lazy loading

## User Experience

### Accessibility

- **Semantic HTML5** structure
- **Keyboard navigation** support
- **Screen reader** compatible
- **Color contrast** compliance

### Usability

- **Intuitive navigation** met clear hierarchy
- **Error handling** met duidelijke feedback
- **Loading states** voor betere UX
- **Mobile optimization** voor touch devices

## Voorbeeld conclusie

- `player_name` en `submitted_from_ip` worden alleen bewaard als strikt noodzakelijk; IP kan gehasht of geanonimiseerd worden voor privacy.
- `player_name` en `submitted_from_ip` worden alleen bewaard als strikt noodzakelijk; IP kan gehasht of geanonimiseerd worden voor privacy.

## Acties

- [ ] Documenteer gekozen auth-methode en waar middleware toegepast is
- [ ] Besluit over IP-retentie en anonimisatie opnemen
