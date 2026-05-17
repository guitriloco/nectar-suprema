# Core Technical Commands & Functions

This document describes the reusable technical components and scripts developed for the "Vendas" project.

## 1. Landing Page Deployment Script
`deploy-lp.sh` - Automates the creation of a new Landing Page based on our boilerplate.

**Usage:**
```bash
./scripts/deploy-lp.sh <project-name>
```

## 2. Niche Management CLI
`niche-manager.py` - Manages niche-specific content (copy, images, links).

**Usage:**
```bash
python3 scripts/niche-manager.py --niche <niche-name> --apply
```

## 3. Payment Integration Module
Standard functions for Kiwify and Hotmart integration.

**Location:** `src/lib/payments.ts`

---

## Technical Details

### deploy-lp.sh
This script:
1. Creates a new directory.
2. Copies the boilerplate from `/home/team/shared/web-structure/`.
3. Installs dependencies.
4. Initializes a local git repository.

### niche-manager.py
Uses a `niches.json` configuration file to update the `src/config/content.json` in the landing page project.

### Payment Integration
Standardized checkout links and tracking parameters (UTM) helper.
