// Combines {date:'YYYY-MM-DD', time:'HH:mm'} into ISO strings
export function formToEventPayload(form) {
  const start_at = `${form.date} ${form.time}:00`

  // If no separate end date/time, add 2 hours to local start time
  let end_at
  if (form.end_date && form.end_time) {
    end_at = `${form.end_date} ${form.end_time}:00`
  } else {
    const startDateObj = new Date(`${form.date}T${form.time}`)
    startDateObj.setHours(startDateObj.getHours() + 2)

    // Convert back to local date/time string
    const yyyy = startDateObj.getFullYear()
    const mm = String(startDateObj.getMonth() + 1).padStart(2, '0')
    const dd = String(startDateObj.getDate()).padStart(2, '0')
    const hh = String(startDateObj.getHours()).padStart(2, '0')
    const min = String(startDateObj.getMinutes()).padStart(2, '0')

    end_at = `${yyyy}-${mm}-${dd} ${hh}:${min}:00`
  }

  return {
    title: form.title,
    description: form.description || null,
    start_at,
    end_at,
    location: form.location || null,
    category: form.category || null,
    status: 'pending',
    capacity: form.capacity ? Number(form.capacity) : null,
    featured: !!form.featured
  }
}

// For displaying an event read from API
export function apiEventToView(e) {
  const start = new Date(e.start_at)
  return {
    ...e,
    date: start.toISOString().slice(0, 10),
    time: start.toTimeString().slice(0, 5)
  }
}
