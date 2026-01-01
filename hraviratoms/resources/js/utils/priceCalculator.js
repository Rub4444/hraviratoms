export function calculateInvitationPrice(template, features, pricingConfig) {
  if (!template) return 0

  let total = Number(template.base_price || 0)

  for (const [key, enabled] of Object.entries(features || {})) {
    if (!enabled) continue

    const feature = pricingConfig?.[key]
    if (!feature) continue

    total += Number(feature.price || 0)
  }

  return total
}
