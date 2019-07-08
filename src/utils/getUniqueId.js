let it = 0;

export default (prefix = 'id') => {
  it++;

  return `${prefix}-${new String(it).padStart(8, "0")}${Math.random() * 1000}${new Date().getUTCMilliseconds() * 1000}`;
}
