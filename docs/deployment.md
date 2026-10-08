# AWS Production Deployment Guide &mdash; MedCase

This guide outlines the production architecture and deployment strategy for hosting the MedCase web application and API on Amazon Web Services (AWS), adhering to medical software security and reliability best practices.

---

## 1. High-Level Architecture Overview

```
 [Users / Browsers / API Clients]
                 |
                 v (HTTPS :443)
      [AWS Application Load Balancer]
      - SSL/TLS Termination (AWS ACM Certificate)
                 |
                 v (HTTP :80 via VPC private subnet)
     [Amazon EC2 Instance(s)]
      - Ubuntu Linux 22.04 LTS / 24.04 LTS
      - Nginx (Reverse Proxy) + PHP 8.2-FPM
      - Supervisor (Queue workers & scheduled tasks)
      - App storage mounted / ephemeral
        |                 |
        | (MySQL :3306)   | (IAM Role / AWS SDK)
        v                 v
 [Amazon RDS for MySQL] [Amazon S3 Bucket]
  - Multi-AZ Deployment    - Surgical documents & CAD models
  - Automated backups      - Server-side AES-256 encryption
  - Encrypted at rest      - Private bucket / Presigned URLs
```

---

## 2. Infrastructure Components

### 1. Compute: Amazon EC2
- **Instance Type:** `t3.small` (for initial workload) or `t3.medium` in an Auto Scaling Group behind an Application Load Balancer (ALB).
- **Web Stack:**
  - **Nginx** configured to serve Laravel's `/public` directory, enforcing strict security headers (`X-Frame-Options`, `X-Content-Type-Options`, `Content-Security-Policy`).
  - **PHP 8.2-FPM** with OPcache enabled for maximum throughput.

### 2. Managed Database: Amazon RDS for MySQL
- **Engine:** MySQL 8.0 / MariaDB 10.11.
- **High Availability:** Multi-AZ deployment for zero-downtime failover and automated point-in-time recovery.
- **Storage Encryption:** AWS KMS (Key Management Service) encryption enabled for patient case data at rest.
- **Network Isolation:** Placed in private DB subnets without public internet accessibility.

### 3. File Storage: Amazon S3
- **Bucket:** Dedicated private bucket for surgical attachments, CAD blueprints, and implant spec sheets (`medcase-clinical-files`).
- **Access Control:** Restricted via AWS IAM roles attached directly to EC2 instances (no long-lived root credentials stored on server).
- **Encryption:** Default S3 bucket encryption (SSE-S3 or SSE-KMS).

---

## 3. Network Security & Security Groups

Strict least-privilege security groups partition external and internal traffic:

| Security Group | Inbound Rules | Outbound Rules | Purpose |
|---|---|---|---|
| `sg-alb` | `443/TCP` (HTTPS from `0.0.0.0/0`), `80/TCP` (Redirects to HTTPS) | All traffic to `sg-app` | Public entry point |
| `sg-app` (EC2) | `80/TCP` from `sg-alb` only; `22/TCP` from Bastion/VPN only | All outbound (updates, S3, APIs) | Application runtime |
| `sg-rds` (MySQL) | `3306/TCP` from `sg-app` only | None | Private database |

---

## 4. Environment Secrets & Configuration Management

1. **No `.env` in Version Control:**
   - Secrets (`APP_KEY`, `DB_PASSWORD`, `DB_USERNAME`) are stored in **AWS Systems Manager Parameter Store** (SecureString) or **AWS Secrets Manager**.
2. **Deployment Injection:**
   - During CI/CD deployment (e.g., GitHub Actions / AWS CodeDeploy), the deployment script pulls secrets using instance IAM credentials and writes the production `.env` file just before running:
     ```bash
     php artisan config:cache
     php artisan route:cache
     php artisan view:cache
     php artisan migrate --force
     ```

---

## 5. HTTPS & SSL/TLS Configuration

1. **Certificate Management:**
   - Free SSL/TLS certificate provisioned via **AWS Certificate Manager (ACM)** with automatic renewal.
2. **ALB Routing:**
   - HTTP requests on port 80 are automatically redirected with `301 Moved Permanently` to port 443 HTTPS.
3. **Transport Security:**
   - TLS protocol version enforced at `TLSv1.2` and `TLSv1.3` with strong cipher suites to comply with medical data transmission standards (HIPAA / GDPR / ISO 27001).
