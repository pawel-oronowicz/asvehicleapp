import axios from 'axios';

const API_BASE = '/vehicles';

export default {
  list() {
    return axios.get(`${API_BASE}/list`);
  },
  save(vehicle) {
    const url = vehicle.id ? `${API_BASE}/save/${vehicle.id}` : `${API_BASE}/save/0`
    return axios.post(url, vehicle);
  },
  delete(id) {
    return axios.delete(`${API_BASE}/${id}`);
  }
}