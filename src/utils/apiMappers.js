// Combines {date:'YYYY-MM-DD', time:'HH:mm'} into ISO strings
export function formToEventPayload(form) {
  if (!form.date || !form.time) {
    throw new Error('Date and time are required')
  }

  const start_at = `${form.date} ${form.time}:00`

  // If no separate end date/time, add 2 hours to start time
  let end_at
  if (form.endDate && form.endTime) {
    end_at = `${form.endDate} ${form.endTime}:00`
  } else {
    // Simple approach: add 2 hours to start time
    const [hours, minutes] = form.time.split(':')
    const endHours = (parseInt(hours) + 2) % 24
    const endTime = `${String(endHours).padStart(2, '0')}:${minutes}`
    end_at = `${form.date} ${endTime}:00`
  }

  const payload = {
    title: form.title,
    description: form.description || null,
    start_at,
    end_at,
    location: form.location || null,
    category: form.category || null,
    capacity: form.capacity ? Number(form.capacity) : null
  }

  return payload
}

// For displaying an event read from API
export function apiEventToView(e) {
  const start = new Date(e.start_at)
  return {
    ...e,
    date: start.toISOString().slice(0, 10),
    time: start.toTimeString().slice(0, 5),
    poster: e.poster_path ? `http://localhost:8000/storage/${e.poster_path}` : null
  }
}
